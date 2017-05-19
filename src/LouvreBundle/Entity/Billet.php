<?php

namespace LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Billet
 *
 * @ORM\Table(name="billet")
 * @ORM\Entity(repositoryClass="LouvreBundle\Repository\BilletRepository")
 */
class Billet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="email")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tarif", type="string", length=255)
     */
    private $tarif;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tarif_reduit", type="boolean")
     */
    private $tarifReduit;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     *
     * @Assert\Country()
     */
    private $pays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date")
     */
    private $birthdate;


    /**
     * @var boolean
     * @ORM\Column(name="demi_journee", type="boolean")
     */
    private $demiJournee;

    /**
     * @var \DateTime
     * @ORM\Column(name="dateDeVenue", type="date")
     */
    private $dateDeVenue;

    /**
     * @var integer
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="billets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     *
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get tarif
     *
     * @return string
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * @param string $tarif
     * @return Billet
     */
    public function setTarif(string $tarif)
    {
        $this->tarif = $tarif;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTarifReduit ()
    {
        return $this->tarifReduit;
    }

    /**
     * @param bool $tarifReduit
     */
    public function setTarifReduit ($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;
    }


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Billet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Billet
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Billet
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Billet
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
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
     * Set dateDeVenue
     *
     * @param \DateTime $dateDeVenue
     *
     * @return Billet
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
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param int $montant
     */
    public function setMontant(int $montant)
    {
        $this->montant = $montant;
    }

    /**
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
