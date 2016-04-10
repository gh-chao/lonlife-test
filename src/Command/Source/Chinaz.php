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
                'hidesel'  => '备案编号',
                's'        => $number,
                'code'     => '',
                'havecode' => 0,
            )
        ));
        $contents = $result->getBody()->getContents();

        $crawler = new Crawler($contents);
        $errorCrawler = $crawler->filterXPath('//*[@id="first"]');
        if (!$errorCrawler->getNode(0)) {
            return false;
        }
        $companyNameCrawler = $crawler->filterXPath('//*[@id="first"]/li[1]/p/text()');
        $companyNatureCrawler = $crawler->filterXPath('//*[@id="first"]/li[2]/p');
        $licenseNumber = $crawler->filterXPath('//*[@id="first"]/li[3]/p/text()');
        $websiteName = $crawler->filterXPath('//*[@id="first"]/li[4]/p');
        $websiteIndex = $crawler->filterXPath('//*[@id="first"]/li[5]/p');
        $checkedDate = $crawler->filterXPath('//*[@id="first"]/li[6]/p');

        return array(
            'company_name'   => $companyNameCrawler->text(),
            'company_nature' => $companyNatureCrawler->text(),
            'license_number' => $licenseNumber->text(),
            'website_name'   => $websiteName->text(),
            'website_index'  => $websiteIndex->text(),
            'checked_date'   => $checkedDate->text(),
        );
    }
}