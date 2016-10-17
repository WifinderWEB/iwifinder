<?php

//src/PiZone/LayoutBundle/Entity/Layout.php

namespace PiZone\LayoutBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PiZone\BackendBundle\Entity\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="layout")
 * @ORM\Entity(repositoryClass="PiZone\LayoutBundle\Entity\Repository\LayoutRepository")
 * @UniqueEntity(fields="name", message="Sorry, this name is already in use.", groups={"Layout"})
 */
class Layout extends Entity{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter name.", groups={"Layout"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter file.", groups={"Layout"})
     */
    protected $file;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
    * @ORM\OneToMany(targetEntity="PiZone\ContentBundle\Entity\Content", mappedBy="layout")
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
     * Set name
     *
     * @param string $name
     * @return Layout
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Layout
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set file
     *
     * @param integer $file
     * @return Layout
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return integer 
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function __toString() {
        return $this->getName();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->content = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add content
     *
     * @param \PiZone\ContentBundle\Entity\Content $content
     * @return Layout
     */
    public function addContent(\PiZone\ContentBundle\Entity\Content $content)
    {
        $this->content[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param \PiZone\ContentBundle\Entity\Content $content
     */
    public function removeContent(\PiZone\ContentBundle\Entity\Content $content)
    {
        $this->content->removeElement($content);
    }

    /**
     * Get content
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Layout
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }
}
