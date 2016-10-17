<?php

//src/PiZone/ContentBundle/Entity/Content.php

namespace PiZone\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PiZone\BackendBundle\Entity\Entity;

/**
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="PiZone\ContentBundle\Entity\Repository\ContentRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Content"})
 * @ORM\HasLifecycleCallbacks
 */
class Content extends Entity {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Content"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Content"}
     * )
     */
    protected $alias;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $anons;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $show_editor_anons = true;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $image;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image_path;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $image_origin_name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $show_editor_content = true;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $big_image;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $big_image_path;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $big_image_origin_name;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\OneToOne(targetEntity="ContentMeta", 
     *   mappedBy="content", 
     *   cascade={"persist", "remove"})
    */
    protected $meta;
    
    /**
     * @ORM\ManyToOne(targetEntity="PiZone\LayoutBundle\Entity\Layout", inversedBy="content")
     * @ORM\JoinColumn(name="layout_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $layout;

    protected $delete_image = false;
    
    protected $delete_big_image = false;
    
    private $temp_image;
    
    private $temp_big_image;

    
    public function getImageAbsolutePath()
    {
        return null === $this->image_path ? null : $this->getUploadRootDir().'/'.$this->image_path;
    }
    
    public function getImageWebPath() {
        return null === $this->image_path ? null : '/' . $this->getUploadDir() . '/' . $this->image_path;
    }
    
    public function getBigImageAbsolutePath()
    {
        return null === $this->big_image_path ? null : $this->getUploadRootDir().'/'.$this->big_image_path;
    }
    
    public function getBigImageWebPath() {
        return null === $this->big_image_path ? null : '/' . $this->getUploadDir() . '/' . $this->big_image_path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/images';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getImage()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->image_path = $filename.'.'.$this->getImage()->guessExtension();
            $this->image_origin_name = $this->getImage()->getClientOriginalName();
        }
        if (null !== $this->getBigImage()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->big_image_path = $filename.'.'.$this->getBigImage()->guessExtension();
            $this->big_image_origin_name = $this->getBigImage()->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getImage() && null === $this->getBigImage()) {
            return;
        }

        if (null !== $this->getImage()){
            $this->getImage()->move($this->getUploadRootDir(), $this->image_path);
            if (isset($this->temp_image)) {
                // delete the old image
                unlink($this->getUploadRootDir().'/'.$this->temp_image);
                // clear the temp image path
                $this->temp_image = null;
            }
            $this->image = null;
        }
        if (null !== $this->big_image){
            $this->getBigImage()->move($this->getUploadRootDir(), $this->big_image_path);
            if (isset($this->temp_big_image)) {
                // delete the old image
                unlink($this->getUploadRootDir().'/'.$this->temp_big_image);
                // clear the temp image path
                $this->temp_big_image = null;
            }
            $this->big_image = null;
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getImageAbsolutePath()) {
            if(file_exists($file))
                unlink($file);
        }
        
        if ($file = $this->getBigImageAbsolutePath()) {
            if(file_exists($file))
                unlink($file);
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
     * @return Content
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
     * Set alias
     *
     * @param string $alias
     * @return Content
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set anons
     *
     * @param string $anons
     * @return Content
     */
    public function setAnons($anons)
    {
        $this->anons = $anons;
    
        return $this;
    }

    /**
     * Get anons
     *
     * @return string 
     */
    public function getAnons()
    {
        return $this->anons;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Content
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
    
    /**
     * Sets file.
     *
     * @param UploadedFile $image
     */
    public function setImage(UploadedFile $image = null)
    {
        $this->image = $image;
        // check if we have an old image path
        if (isset($this->image_path)) {
            // store the old name to delete after the update
            $this->temp_image = $this->image_path;
            $this->image_path = null;
        } else {
            $this->image_path = 'initial';
        }
    }


    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $big_image
     */
    public function setBigImage(UploadedFile $big_image = null)
    {
        $this->big_image = $big_image;
        // check if we have an old image path
        if (isset($this->big_image_path)) {
            // store the old name to delete after the update
            $this->temp_big_image = $this->big_image_path;
            $this->big_image_path = null;
        } else {
            $this->big_image_path = 'initial';
        }
    }
    

    /**
     * Get big_image
     *
     * @return string 
     */
    public function getBigImage()
    {
        return $this->big_image;
    }

    /**
     * Set image_path
     *
     * @param string $imagePath
     * @return Content
     */
    public function setImagePath($imagePath)
    {
        $this->image_path = $imagePath;
    
        return $this;
    }

    /**
     * Get image_path
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->image_path;
    }

    /**
     * Set image_origin_name
     *
     * @param string $imageOriginName
     * @return Content
     */
    public function setImageOriginName($imageOriginName)
    {
        $this->image_origin_name = $imageOriginName;
    
        return $this;
    }

    /**
     * Get image_origin_name
     *
     * @return string 
     */
    public function getImageOriginName()
    {
        return $this->image_origin_name;
    }

    /**
     * Set big_image_path
     *
     * @param string $bigImagePath
     * @return Content
     */
    public function setBigImagePath($bigImagePath)
    {
        $this->big_image_path = $bigImagePath;
    
        return $this;
    }

    /**
     * Get big_image_path
     *
     * @return string 
     */
    public function getBigImagePath()
    {
        return $this->big_image_path;
    }

    /**
     * Set big_image_origin_name
     *
     * @param string $bigImageOriginName
     * @return Content
     */
    public function setBigImageOriginName($bigImageOriginName)
    {
        $this->big_image_origin_name = $bigImageOriginName;
    
        return $this;
    }

    /**
     * Get big_image_origin_name
     *
     * @return string 
     */
    public function getBigImageOriginName()
    {
        return $this->big_image_origin_name;
    }
    
    public function getDeleteImage(){
        return $this->delete_image;
    }
    
    public function setDeleteImage($delete){
        if($delete && null === $this->getImage()){
            if ($file = $this->getImageAbsolutePath()) {
                $this->setImagePath(null);
                if(file_exists($file))
                    unlink($file);
            }
        }        
    }
    
    public function setDeleteBigImage($delete){
        if($delete && null === $this->getBigImage()){
            if ($file = $this->getBigImageAbsolutePath()) {
                $this->setBigImagePath(null);
                if(file_exists($file))
                    unlink($file);
            }
        }
    }
    
    public function getDeleteBigImage(){
        return $this->delete_big_image;
    }

    /**
     * Set show_editor_anons
     *
     * @param boolean $showEditorAnons
     * @return Content
     */
    public function setShowEditorAnons($showEditorAnons)
    {
        $this->show_editor_anons = $showEditorAnons;

        return $this;
    }

    /**
     * Get show_editor_anons
     *
     * @return boolean 
     */
    public function getShowEditorAnons()
    {
        return $this->show_editor_anons;
    }

    /**
     * Set show_editor_content
     *
     * @param boolean $showEditorContent
     * @return Content
     */
    public function setShowEditorContent($showEditorContent)
    {
        $this->show_editor_content = $showEditorContent;

        return $this;
    }

    /**
     * Get show_editor_content
     *
     * @return boolean 
     */
    public function getShowEditorContent()
    {
        return $this->show_editor_content;
    }

    /**
     * Set meta
     *
     * @param \PiZone\ContentBundle\Entity\ContentMeta $meta
     * @return Content
     */
    public function setMeta(\PiZone\ContentBundle\Entity\ContentMeta $meta = null)
    {
        $this->meta = $meta;
        $meta->setContent($this);

        return $this;
    }

    /**
     * Get meta
     *
     * @return \PiZone\ContentBundle\Entity\ContentMeta 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set layout
     *
     * @param \PiZone\LayoutBundle\Entity\Layout $layout
     * @return Content
     */
    public function setLayout(\PiZone\LayoutBundle\Entity\Layout $layout = null)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return \PiZone\LayoutBundle\Entity\Layout 
     */
    public function getLayout()
    {
        return $this->layout;
    }
}
