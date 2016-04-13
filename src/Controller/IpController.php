<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IpController
{

    public function indexAction(Application $app)
    {
        $count = $app['db']->query("select COUNT(id) from ip;")->fetchColumn();

        return $app['twig']->render(
            'index.html.twig',
            array(
                'count' => $count,
            )
        );
    }

    public function queryAction()
    {
        return JsonResponse::create(
            [
                'status'  => 0,
                'message' => '哈哈哈',
            ]
        );
    }

    public function importAction(Application $app)
    {
        return $app['twig']->render(
            'import.html.twig',
            array()
        );
    }

    public function uploadAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        var_dump($_POST);

//        $fp = fopen($file->getRealPath(), 'r');
//
//        var_dump($fp);
//
//        while(($line = fgets($fp)) !== false) {
//            var_dump($line);
//        }
//
//        fclose($fp);
//exit;
        return JsonResponse::create(['status'=> 0]);
    }
}