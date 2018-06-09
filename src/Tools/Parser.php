<?php
/**
 * Created by PhpStorm.
 * User: gorshkov-ag
 * Date: 08.06.2018
 * Time: 13:26
 */

namespace App\Tools;


use App\Entity\NewsItem;
use App\Entity\Site;
use http\Url;
use Monolog\Logger;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Parser
 * @package App\Tools
 */
class Parser
{
    /**
     * @param Crawler $crawler
     * @param Site $site
     * @return \ArrayObject
     */
    public static function parseNewsItems(Crawler $crawler, Site $site) :\ArrayObject {

        /** @var array $config */
        $config = $site->getConfig();
        $newsItems = new \ArrayObject();

        $newsItemBlocks = $crawler->filter($config['news_block_filter']);

        foreach ($newsItemBlocks as $domElement) {
            $title = self::parseTitle($domElement, $config['title_tag'], $config['title_class']);

            $link = self::parseLink($domElement, $config['link_tag'], $config['link_class']);
            $link = $config['link_base'].$link;

            $date = self::parseDate($domElement, $config['pub_date_tag'], $config['pub_date_class'],
                $config['pub_date_format']);

            $newsItem = new NewsItem($site->getId(), $title);
            $newsItem->setLink($link);
            $newsItem->setPubDate($date);

            $newsItems->append($newsItem);
        }

        return $newsItems;
    }

    private static function parseTitle(\DOMElement $domElement, string $tag, string $class) : string {
        /** @var \DOMElement $element */
        foreach ($domElement->getElementsByTagName($tag) as $element) {
            if (stripos($element->getAttribute('class'), $class)!==false) {
                return $element->textContent;
            }
        }
    }

    private static function parseLink(\DOMElement $domElement, string $tag, string $class) : string {
        /** @var \DOMElement $element */
        foreach ($domElement->getElementsByTagName($tag) as $element) {
            if (stripos($element->getAttribute('class'), $class)!==false) {
                return $element->getAttribute('href');
            }
        }
    }

    private static function parseDate(\DOMElement $domElement, string $tag, string $class, string $format) : \DateTime {
        $months = [
            'января' => '01',
            'февраля' => '02',
            'марта' => '03',
            'апреля' => '04',
            'мая' => '05',
            'июня' => '06',
            'июля' => '07',
            'августа' => '08',
            'сентября' => '09',
            'октября' => '10',
            'ноября' => '11',
            'декабря' => '12',

        ];
        /** @var \DOMElement $element */
        foreach ($domElement->getElementsByTagName($tag) as $element) {
            if (stripos($element->getAttribute('class'), $class)!==false) {
                /** @var string $dateString */
                $dateString = $element->textContent;
                $dateString = trim(str_replace(array_keys($months),
                    array_values($months),
                    strtolower($dateString)));

                return \DateTime::createFromFormat($format, $dateString);
            }
        }
    }
}