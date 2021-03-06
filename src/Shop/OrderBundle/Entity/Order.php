<?php

namespace Shop\OrderBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Order
 * @package Shop\ApiBundle\Entity
 * @ORM\Entity
 */
class Order{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

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
     * @ORM\OneToMany(targetEntity="Shop\ApiBundle\Entity\Goods", mappedBy="order")
     */
    protected $goods;
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
     * @param \Shop\ApiBundle\Entity\Goods $goods
     * @return Order
     */
    public function addGood(\Shop\ApiBundle\Entity\Goods $goods)
    {
        $this->goods[] = $goods;
        $goods->setOrder($this);

        return $this;
    }

    /**
     * Remove goods
     *
     * @param \Shop\ApiBundle\Entity\Goods $goods
     */
    public function removeGood(\Shop\ApiBundle\Entity\Goods $goods)
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
}
