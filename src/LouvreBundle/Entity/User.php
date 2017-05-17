<?php

namespace LouvreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\OneToMany(targetEntity="Billet", mappedBy="user", cascade={"persist"})
     */
    protected $billets;

    /**
     * @ORM\Column(name="Total", type="integer")
     */
    protected $total;

    /**
     * @ORM\Column(name="order_date", type="datetime")
     */
    protected $orderDate;

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
     * @param mixed $billets
     */
    public function addBillets($billets)
    {
        foreach ( $billets as $billet) {
            $visitorBirth = $billet->getBirthdate();
            $visitorVenue = $billet->getDateDeVenue();

            $tarifReduit  = $billet->isTarifReduit();

            if ($tarifReduit == true)
            {
                $tarif = 'réduit';
                $montant = 10;
            }
            else
            {
                $age = $visitorVenue->diff($visitorBirth)->y;
                switch ($age) {
                    case ($age<4):
                        $tarif = 'gratuit';
                        $montant = 0;
                        break;
                    case ($age>=4 && $age <= 12):
                        $tarif = 'enfant';
                        $montant = 8;
                        break;
                    case ($age > 12 && $age < 60):
                        $tarif = 'normal';
                        $montant = 16;
                        break;
                    case ($age >= 60):
                        $tarif = 'senior';
                        $montant = 12;
                        break;
                }
            }





            $billet->setMontant($montant);
            $billet->setTarif($tarif);
            $billet->setUser($this);
            dump($billet);
            $this->$billets[] = $billet;
        }
    }

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
}

