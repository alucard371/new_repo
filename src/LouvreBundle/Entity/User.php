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
            $age = $visitorVenue->diff($visitorBirth)->y;
            switch ($age) {
                case ($age<4):
                    $tarif = 'gratuit';
                    $montant = 0;
                    break;
                case ($age>=4 && $age<12):
                    $tarif = 'enfant';
                    $montant = 8;
                    break;
                case ($age >= 12 && $age < 60):
                    $tarif = 'normal';
                    $montant = 16;
                    break;
                case ($age >= 60):
                    $tarif = 'senior';
                    $montant = 12;
                    break;
            }

            if ($visitorVenue->format('D') == 'tue'){
                echo "choississez un autre jour";
            }

            if ($visitorVenue->format('H') >= 14){
                $demiJournee = true;
                $montant = $montant/2;
            }
            else {
                $demiJournee = false;
            }

            $billet->setDemiJournee($demiJournee);
            $billet->setMontant($montant);
            $billet->setTarif($tarif);
            $billet->setUser($this);
            dump($billet);
            $this->$billets[] = $billet;
        }
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
}

