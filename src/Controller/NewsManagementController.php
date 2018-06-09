<?php
/**
 * Created by PhpStorm.
 * User: gorshkov-ag
 * Date: 08.06.2018
 * Time: 21:07
 */

namespace App\Controller;


use App\Entity\NewsItem;
use App\Entity\Site;
use App\Tools\Downloader;
use App\Tools\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class NewsManagementController extends Controller
{
    public function updateAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Site[] $sites */
        $sites = $em->getRepository(Site::class)->findAll();

        if (count($sites) == 0) {
            $this->createBaseSite();
        } else {
            foreach ($sites as $site) {
                $this->updateNewsItems($site);
            }
        }

        return $this->redirectToRoute('index');
    }

    private function createBaseSite()
    {
        $baseSite = new Site('Прогород Владимир', 'https://progorod33.ru/news');

        $config = [
            'news_block_filter' => 'div[class~="article-list__item"]',
            'title_tag' => 'a',
            'title_class' => 'link_nodecor',
            'link_tag' => 'a',
            'link_class' => 'link_nodecor',
            'link_base' => 'https://progorod33.ru',
            'pub_date_tag' => 'span',
            'pub_date_class' => 'article-list__item-date',
            'pub_date_format' => 'd m Y, H:i'
        ];

        $baseSite->setConfig($config);

        $em = $this->getDoctrine()->getManager();
        $em->persist($baseSite);
        $em->flush();

        $this->updateNewsItems($baseSite);
    }


    /**
     * @param Site $site
     */
    private function updateNewsItems(Site $site)
    {
        $crawler = Downloader::getContent($site);
        $newsItems = Parser::parseNewsItems($crawler, $site);

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(NewsItem::class);
        /** @var NewsItem $newsItem */
        $count = 0;
        foreach ($newsItems as $newsItem) {
            if (is_null($repository->findOneBy([
                'title' => $newsItem->getTitle(),
                'pubDate' => $newsItem->getPubDate()
            ]))) {
                $em->persist($newsItem);
            }
        }
        $site->setLastUpdate(new \DateTime());
        $em->flush();
    }
}