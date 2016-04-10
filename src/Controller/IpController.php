<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IpController
{

    // 首页
    public function indexAction(Application $app)
    {
        return $app['twig']->render(
            'index.html.twig',
            array(
                'count' => $app['mongo_collection']->count(),
            )
        );
    }

    // 查询接口
    public function queryAction(Application $app, Request $request)
    {
        $ip = trim($request->query->get('ip'));
        $mask = null;
        if (strpos($ip, '/')!==false) {
            list($ip, $mask) = explode('/', $ip);
        }

        if (!preg_match('/^((25[0-5]|2[0-4]\d|[01]?\d\d?)($|(?!\.$)\.)){4}$/', $ip)) {
            // ip
            return JsonResponse::create([
                'status'  => -1,
                'message' => 'ip格式错误',
            ]);
        }

        if ($mask && (!is_numeric($mask) || $mask > 32 || $mask < 1)) {
            return JsonResponse::create([
                'status'  => -1,
                'message' => 'ip格式错误',
            ]);
        }

        $number = $this->ip2number($ip);

        if ($mask) {
            $proper_address = bcadd(pow(2, 32 - $mask), sprintf("%u", ip2long($ip))) - 2;
            $number_right   = $this->ip2number(long2ip($proper_address));
            $result = $app['mongo_collection']->findOne(array(
                'ip_left'  => array('$lte' => $number),
                'ip_right' => array('$gte' => $number_right),
            ));
        } else {
            $result = $app['mongo_collection']->findOne(array(
                'ip_left'  => array('$lte' => $number),
                'ip_right' => array('$gte' => $number),
            ));
        }

        if ($result) {
            return JsonResponse::create([
                'status'  => 0,
                'message' => base64_decode($result['address']),
            ]);
        } else {
            return JsonResponse::create([
                'status'  => -1,
                'message' => '无结果',
            ]);
        }
    }

    // 上传页面
    public function importAction(Application $app)
    {
        return $app['twig']->render(
            'import.html.twig',
            array()
        );
    }

    // 上传接口
    public function uploadAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        if (!$file) {
            return JsonResponse::create(['status' => -1, 'message' => '上传失败']);
        }

        $fp = fopen($file->getRealPath(), "rb");
        $bin = fread($fp, 2);
        fclose($fp);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        if ($typeCode != 239187) {
            return JsonResponse::create(['status' => -1, 'message' => '文件类型错误, 请上传UTF-8编码的文本文档']);
        }

        $save = md5($file->getRealPath());
        $file->move(realpath(__DIR__ . '/../../data/'), $save);

        return JsonResponse::create(['status' => 0, 'save' => $save]);
    }

    // 导入接口
    public function doImportAction($file, Application $app)
    {
        header('Content-Type:text/html; charset=utf-8');
        set_time_limit(0);
        $filename = realpath(__DIR__ . '/../../data/') . '/' . $file;
        if (!file_exists($filename)) {
            return '文件不存在';
        }
        $fp = fopen($filename, 'r');

        if (!$fp) {
            return '导入失败： 007';
        }
        printf("<span style='color: red'>导入过程请勿关闭浏览器</span><br/>\n");
        $n = 0;
        $begin_time = time();
        while ($line = fgets($fp)) {
            $columns = preg_split('/\s+/', $line, 3);
            if (count($columns) != 3) {
                continue;
            }
            $ip_left = $this->ip2number($columns[0]);
            $ip_right = $this->ip2number($columns[1]);

            $app['mongo_collection']->save(array(
                '_id'      => $ip_left . $ip_right,
                'ip_left'  => $ip_left,
                'ip_right' => $ip_right,
                'address'  => base64_encode(trim($columns[2])),
            ));
            $n++;
            if ($n % 5000 == 0) {
                printf("已导入： %d<br/>\n", $n);
                ob_flush();
            }
        }

        fclose($fp);
        return  sprintf("<span style='color: green'>导入成功，共导入： %d条数据, 用时 %d秒</span><br/>\n", $n, time()-$begin_time);
    }

    // ip转为数字
    private function ip2number($ip)
    {
        $array = explode('.', $ip);
        return sprintf('%03d%03d%03d%03d', $array[0], $array[1], $array[2], $array[3]);
    }
}