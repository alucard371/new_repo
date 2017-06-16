<?php

namespace LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LouvreBundle\Validators\DemiJournee;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="LouvreBundle\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     * @ORM\OneToMany(targetEntity="Billet", mappedBy="id")
     * @Assert\Email()
     */
    private $email;

    /**
     * @var \DateTime
     * @ORM\Column(name="dateDeVenue", type="date")
     */
    private $dateDeVenue;

    /**
     * @ORM\OneToMany(targetEntity="Billet", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $billets;

    /**
     * @ORM\Column(name="nombre_billets", type="integer")
     * @var integer
     */
    private $nombreBillets;

    /**
     * @ORM\Column(name="Total", type="integer")
     */
    protected $total;

    /**
     * @ORM\Column(name="order_date", type="datetime")
     */
    private $orderDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="validated", type="boolean", nullable=true)
     */
    private $validated;

    /**
     * @var string
     * @ORM\Column(name="order_number", type="string", nullable=true)
     */
    private $orderNumber;

    /**
     * @var array
     *
     * @ORM\Column(name="current_place", type="array")
     */
    private $currentPlace;

    /**
     * @var boolean
     * @ORM\Column(name="demi_journee", type="boolean")
     */
    private $demiJournee;

    /**
     * @param $billets
     * @return int
     */
    public function getTotal ($billets)
    {
        $total = 0;
        foreach ( $billets as $billet) {
            $total = $total + $billet->getMontant();
        }
        return $total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal ($total)
    {
        $this->total = $total;
    }


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->billets = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getBillets()
    {
        return $this->billets;
    }


    /**
     * @param Billet $billet
     * @return $this
     * @internal param mixed $billets
     */
    public function addBillets(Billet $billet)
    {
        /** @var Billet $billets */
        $this->$billets[] = $billet;
            return $this;
    }

    /**
     * @param Billet $billet
     */
    public function removeBillet (Billet $billet)
    {
        $this->billets->removeElement($billet);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getOrderDate ()
    {
        return $this->orderDate;
    }

    /**
     * @param mixed $orderDate
     */
    public function setOrderDate ($orderDate)
    {
        $this->orderDate = $orderDate;
    }

    /**
     * @return bool
     */
    public function isValidated ()
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     */
    public function setValidated (bool $validated)
    {
        $this->validated = $validated;
    }

    /**
     * @return string
     */
    public function getOrderNumber ()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     */
    public function setOrderNumber (string $orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * @return array
     */
    public function getCurrentPlace ()
    {
        return $this->currentPlace;
    }

    /**
     * @param array $currentPlace
     */
    public function setCurrentPlace (array $currentPlace)
    {
        $this->currentPlace = $currentPlace;
    }

    /**
     * Set dateDeVenue
     *
     * @param \DateTime $dateDeVenue
     *
     * @return User
     */
    public function setDateDeVenue($dateDeVenue)
    {
        $this->dateDeVenue = $dateDeVenue;

        return $this;
    }

    /**
     * Get dateDeVenue
     *
     * @return \DateTime $dateDeVenue
     */
    public function getDateDeVenue()
    {
        return $this->dateDeVenue;
    }

    /**
     * @return bool
     */
    public function isDemiJournee ()
    {
        return $this->demiJournee;
    }

    /**
     * @param bool $demiJournee
     */
    public function setDemiJournee (bool $demiJournee)
    {
            $this->demiJournee = $demiJournee;
    }

    /**
     * @return int
     */
    public function getNombreBillets (): int
    {
        return $this->nombreBillets;
    }

    /**
     * @param int $nombreBillets
     */
    public function setNombreBillets (int $nombreBillets)
    {
        $this->nombreBillets = $nombreBillets;
    }



}

