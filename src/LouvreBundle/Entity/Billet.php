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
     * @ORM\Column(name="tarif", type="string", length=255, nullable=false)
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
     */
    private $pays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date")
     */
    private $birthdate;


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
    /**
     * Set ticket price
     *
     * @param integer $age
     * @param bool $reduit
     * @return int
     */
    public function setTarif ($age, $reduit)
    {
        switch ($age) {
            case (  $age>0 && $age < 4 ):
                $this->tarif = "gratuit";
                break;
            case ($age>=4 && $age <= 12):
                $this->tarif = "enfant";
                break;
            case ($age >= 60):
                $this->tarif = "senior";
                break;
            case ($reduit === true) :
                $this->tarif = "réduit";
                break;
            default :
                $this->tarif = "normal";
                break;
        }

        return $this->tarif;
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
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param $tarif
     * @return int
     * @internal param int $montant
     */
    public function SetMontant ($tarif)
    {
        switch ($tarif) {
            case ($tarif === "normal"):
                $this->montant = 16;
                break;
            case ($tarif === "enfant"):
                $this->montant = 8;
                break;
            case ($tarif === "senior"):
                $this->montant = 12;
                break;
            case ($tarif === "réduit"):
                $this->montant = 10;
                break;
            case ($tarif === "gratuit"):
                $this->montant = 0;
                break;
        }
        return $this->montant;
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
