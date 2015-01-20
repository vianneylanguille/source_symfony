<?php

namespace eclore\userBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsPost
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class NewsPost
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishDate", type="date")
     */
    private $publishDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="text")
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;
    
    /**
     * @ORM\ManyToMany(targetEntity="eclore\userBundle\Entity\Image", cascade={"persist"})
     */
    private $pictures;

    public function __construct()
    {
    $this->pictures = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function addPicture(\eclore\userBundle\Entity\Image $image) 
    {
            $this->pictures[] = $image;
    }
  
    public function removePicture(\eclore\userBundle\Entity\Image $image) 
    {
      $this->pictures->removeElement($image);
    }

    public function getPictures()
    {
        return $this->pictures;
    }
    
    public function setPictures($pics)
    {
        foreach ($pics as $pic) {
            $this->addPicture($pic);
        }
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return NewsPost
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set publishDate
     *
     * @param \DateTime $date
     * @return NewsPost
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get publishDate
     *
     * @return \DateTime 
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }
    
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set header
     *
     * @param string $header
     * @return NewsPost
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string 
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return NewsPost
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
    

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
}
