<?php
/**
 * Created by PhpStorm.
 * User: gorshkov-ag
 * Date: 07.06.2018
 * Time: 15:18
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class NewsItem
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\NewsItemsRepository")
 * @ORM\Table(name="news")
 */
class NewsItem
{
    /**
     * @var int $id
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var int $siteId
     * @ORM\Column(type="integer")
     */
    private $siteId;
    /**
     * @var string $title
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @var string $description
     * @ORM\Column(type="string", nullable=true)
     */
    private $link;
    /**
     * @var \DateTime $pubDate
     * @ORM\Column(type="datetime", name="pub_date", nullable=true)
     */
    private $pubDate;
    /**
     * @var \DateTime $addDate
     * @ORM\Column(type="datetime", name="add_date")
     */
    private $addDate;

    /**
     * NewsItem constructor.
     * @param int $siteId
     * @param string $title
     */
    public function __construct(int $siteId, string $title)
    {
        $this->siteId = $siteId;
        $this->title = $title;
        $this->addDate = new \DateTime();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSiteId(): int
    {
        return $this->siteId;
    }

    /**
     * @param int $siteId
     */
    public function setSiteId(int $siteId): void
    {
        $this->siteId = $siteId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return \DateTime
     */
    public function getPubDate(): \DateTime
    {
        return $this->pubDate;
    }

    /**
     * @param \DateTime $pubDate
     */
    public function setPubDate(\DateTime $pubDate): void
    {
        $this->pubDate = $pubDate;
    }

    /**
     * @return \DateTime
     */
    public function getAddDate(): \DateTime
    {
        return $this->addDate;
    }

    /**
     * @param \DateTime $addDate
     */
    public function setAddDate(\DateTime $addDate): void
    {
        $this->addDate = $addDate;
    }
}