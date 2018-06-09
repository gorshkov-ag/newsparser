<?php
/**
 * Created by PhpStorm.
 * User: gorshkov-ag
 * Date: 08.06.2018
 * Time: 13:26
 */

namespace App\Tools;

use App\Entity\Site;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Downloader
{
    public static function getContent(Site $site) : Crawler {
        $client = new Client();
        $crawler = $client->request('GET', $site->getUrl());

        return $crawler;
    }
}