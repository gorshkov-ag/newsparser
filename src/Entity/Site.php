<?php
/**
 * Created by PhpStorm.
 * User: gorshkov-ag
 * Date: 07.06.2018
 * Time: 15:43
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Site
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="sites")
 */
class Site
{
    /**
     * @var int $id
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string $name
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var string $url
     * @ORM\Column(type="string")
     */
    private $url;
    /**
     * @var \DateTime $lastUpdate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUpdate;
    /**
     * @var array $config;
     * @ORM\Column(type="array")
     */
    private $config;

    /**
     * Site constructor.
     * @param string $name
     * @param string $url
     */
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdate(): \DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * @param \DateTime $lastUpdate
     */
    public function setLastUpdate(\DateTime $lastUpdate): void
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

}