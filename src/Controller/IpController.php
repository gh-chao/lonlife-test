<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

class IpController
{

    public function indexAction(Application $app)
    {
        $count = $app['db']->query("select COUNT(*) from ip;")->fetchColumn();

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
}