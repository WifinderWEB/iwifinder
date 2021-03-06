<?php
// src/PiZone/UserBundle/Entity/User.php

namespace PiZone\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", length=225, nullable=true)
     * @Assert\Regex( 
     *       pattern="/^(([a-zA-Zа-яА-ЯёЁ]+)\s)+?([a-zA-Zа-яА-ЯёЁ]+)$/",
     *       message="Field can contain only letters."
     * )
     */
    protected $full_name;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $phone;
    
    /**
     * @var boolean
     */
    protected $enabled = true;

    /**
     * @ORM\OneToMany(targetEntity="Shop\OrderBundle\Entity\Order", mappedBy="user", cascade={"remove", "persist"})
     */
    protected $orders;


    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set full_name
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;
    
        return $this;
    }

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    
    public function getEnabled(){
        return $this->enabled;
    }

    /**
     * Add orders
     *
     * @param \Shop\OrderBundle\Entity\Order $orders
     * @return User
     */
    public function addOrder(\Shop\OrderBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Shop\OrderBundle\Entity\Order $orders
     */
    public function removeOrder(\Shop\OrderBundle\Entity\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
