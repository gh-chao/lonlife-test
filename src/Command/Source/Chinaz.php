<?php

namespace Command\Source;

use Symfony\Component\DomCrawler\Crawler;

class Chinaz
{
    const url = 'http://icp.chinaz.com/';
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Chinaz constructor.
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function crawler($number)
    {
        $result = $this->client->post(self::url, array(
            'form_params' => array(
                'hidesel' => '备案编号',
                's' => '京ICP证030175号',
                'code' => '',
                'havecode' => 0,
            )
        ));
        $contents = $result->getBody()->getContents();

        $crawler = new Crawler($contents);
        $nameCrawler = $crawler->filterXPath('//*[@id="first"]/li[1]/p/text()');

        echo $nameCrawler->text();
    }
}