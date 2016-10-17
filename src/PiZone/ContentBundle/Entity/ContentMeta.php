<?php
// src/PiZone/ContentBundle/Entity/ContentMeta.php

namespace PiZone\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="content_meta")
 * @ORM\Entity(repositoryClass="PiZone\ContentBundle\Entity\Repository\ContentMetaRepository")
 */
class ContentMeta {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_title; 
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_keywords;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $meta_description;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $more_scripts;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $in_site_map;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $in_robots;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $in_breadcrumbs;
    /**
     * @ORM\OneToOne(targetEntity="Content", inversedBy="meta")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    protected $content;
    
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
     * Set meta_title
     *
     * @param string $metaTitle
     * @return ContentMeta
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;
    
        return $this;
    }

    /**
     * Get meta_title
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return ContentMeta
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return ContentMeta
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
    
        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set content
     *
     * @param \PiZone\ContentBundle\Entity\Content $content
     * @return ContentMeta
     */
    public function setContent(\PiZone\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return \PiZone\ContentBundle\Entity\Content 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set more_scripts
     *
     * @param string $moreScripts
     * @return ContentMeta
     */
    public function setMoreScripts($moreScripts)
    {
        $this->more_scripts = $moreScripts;
    
        return $this;
    }

    /**
     * Get more_scripts
     *
     * @return string 
     */
    public function getMoreScripts()
    {
        return $this->more_scripts;
    }

    /**
     * Set in_site_map
     *
     * @param boolean $inSiteMap
     * @return ContentMeta
     */
    public function setInSiteMap($inSiteMap)
    {
        $this->in_site_map = $inSiteMap;
    
        return $this;
    }

    /**
     * Get in_site_map
     *
     * @return boolean 
     */
    public function getInSiteMap()
    {
        return $this->in_site_map;
    }

    /**
     * Set in_robots
     *
     * @param boolean $inRobots
     * @return ContentMeta
     */
    public function setInRobots($inRobots)
    {
        $this->in_robots = $inRobots;
    
        return $this;
    }

    /**
     * Get in_robots
     *
     * @return boolean 
     */
    public function getInRobots()
    {
        return $this->in_robots;
    }

    /**
     * Set in_breadcrumbs
     *
     * @param boolean $inBreadcrumbs
     * @return ContentMeta
     */
    public function setInBreadcrumbs($inBreadcrumbs)
    {
        $this->in_breadcrumbs = $inBreadcrumbs;
    
        return $this;
    }

    /**
     * Get in_breadcrumbs
     *
     * @return boolean 
     */
    public function getInBreadcrumbs()
    {
        return $this->in_breadcrumbs;
    }
}
