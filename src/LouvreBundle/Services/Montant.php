<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 15/05/2017
 * Time: 11:06
 */


namespace LouvreBundle\Services;

use LouvreBundle\Entity\User;

class Montant
{
    /**
     * @var integer
     */
    private $tarif;

    /**
     * @var integer
     */
    private $orderTotal;


    /**
     * @param \DateTime $birthdate
     * @param \DateTime $dateDeVenue
     * @return int
     */
    public function setAge ($birthdate, $dateDeVenue)
    {
        try {
            if (!is_object($birthdate) && !is_object($dateDeVenue)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Wrong value passed through the method, should be an object, given "%s"', gettype([$birthdate, $dateDeVenue])
                    )
                );
            }
        } catch (\InvalidArgumentException $exception) {
            $exception->getMessage();
        }
        return date_diff($birthdate, $dateDeVenue)->y;
    }

    /**
     * @param User $user
     * @return User
     */
    public function setMontantForOrder (User $user)
    {
        $tickets = $user->getBillets();
        foreach ($tickets as $ticket) {

            $age = $this->setAge($ticket->getBirthdate(), $ticket->getDateDeVenue());
            $tarif = $this->setTarif($age, $ticket->isTarifReduit());
            $ticket->setMontant($tarif);

            //define the order price
            $this->orderTotal += $ticket->getMontant();
            $user->setTotal($this->orderTotal);
            //link tickets to an user
            $ticket->setUser($user);

        }
        return $user;
    }

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
            case ($age<4):
                $this->tarif = 0;
                break;
            case ($age>=4 && $age <= 12):
                $this->tarif = 8;
                break;
            case ($age >= 60):
                $this->tarif = 12;
                break;
            case $reduit :
                $this->tarif = 10;
                break;
            default :
                $this->tarif = 16;
                break;
        }
        return $this->tarif;
    }
}