<?php

namespace Shop\OrderBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="wf_order")
 * @ORM\Entity(repositoryClass="Shop\OrderBundle\Entity\Repository\OrderRepository")
 */
class Order{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $project_id = 1;

    /**
     * @ORM\ManyToOne(targetEntity="PiZone\UserBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter country.", groups={"Order"})
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter region.", groups={"Order"})
     */
    protected $region;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter city.", groups={"Order"})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter street.", groups={"Order"})
     */
    protected $street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter house.", groups={"Order"})
     */
    protected $house;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $room;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *       pattern="/^[\d]{6}$/",
     *       message="Wrong code.",
     *       groups={"Order"}
     * )
     */
    protected $postcode;

    /**
     * @ORM\Column(type="float")
     */
    protected $discount;

    /**
     * @ORM\Column(type="float")
     */
    protected $itog;

    /**
     * @ORM\OneToMany(targetEntity="Shop\OrderBundle\Entity\Goods", mappedBy="order", cascade={"remove", "persist"})
     */
    protected $goods;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $status;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->goods = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Order
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set country
     *
     * @param string $country
     * @return Order
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Order
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Order
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Order
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set house
     *
     * @param string $house
     * @return Order
     */
    public function setHouse($house)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get house
     *
     * @return string 
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set room
     *
     * @param string $room
     * @return Order
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return string 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Order
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Add goods
     *
     * @param \Shop\OrderBundle\Entity\Goods $goods
     * @return Order
     */
    public function addGood(\Shop\OrderBundle\Entity\Goods $goods)
    {
        $this->goods[] = $goods;
        $goods->setOrder($this);

        return $this;
    }

    /**
     * Remove goods
     *
     * @param \Shop\OrderBundle\Entity\Goods $goods
     */
    public function removeGood(\Shop\OrderBundle\Entity\Goods $goods)
    {
        $this->goods->removeElement($goods);
    }

    /**
     * Get goods
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoods()
    {
        return $this->goods;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Order
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer 
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set itog
     *
     * @param float $itog
     * @return Order
     */
    public function setItog($itog)
    {
        $this->itog = $itog;

        return $this;
    }

    /**
     * Get itog
     *
     * @return float 
     */
    public function getItog()
    {
        return $this->itog;
    }

    /**
     * Set user
     *
     * @param \PiZone\UserBundle\Entity\User $user
     * @return Order
     */
    public function setUser(\PiZone\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PiZone\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Order
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Order
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set project_id
     *
     * @param integer $projectId
     * @return Order
     */
    public function setProjectId($projectId)
    {
        $this->project_id = $projectId;

        return $this;
    }

    /**
     * Get project_id
     *
     * @return integer
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        if($this->status)
            return $this->status;
        else
            return 'Новый';
    }

    public function getFullName(){
        if($this->getUser())
            return $this->getUser()->getFullName();
        return '';
    }

    public function getAddress(){
        $result  = array();
        if($this->getCountry())
            $result[] = $this->getCountry();
        if($this->getRegion())
            $result[] = $this->getRegion();
        if($this->getCity())
            $result[] = $this->getCity();
        if($this->getStreet())
            $result[] = 'ул. ' . $this->getStreet();
        if($this->getHouse())
            $result[] = 'дом № ' . $this->getHouse();
        if($this->getRoom())
            $result[] = 'кв. ' . $this->getRoom();
        if($this->getPostcode())
            $result[] = 'почтовый индекс ' . $this->getPostcode();

        return implode(', ', $result);
    }
}
