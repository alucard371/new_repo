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
                        'Mauvaise valeur passée, donnée "%s"', gettype([$birthdate, $dateDeVenue])
                    )
                );
            }
        } catch (\InvalidArgumentException $exception) {
            $exception->getMessage();
        }
        return ($birthdate->diff($dateDeVenue))->y;
    }

    /**
     * @param User $user
     * @return User
     */
    public function setMontantForOrder (User $user)
    {
        $tickets = $user->getBillets();
        foreach ($tickets as $ticket) {

            $age = $this->setAge($ticket->getBirthdate(), $user->getDateDeVenue());
            $tarif = $ticket->setTarif($age, $ticket->isTarifReduit());
            $ticket->setMontant($tarif);

            //define the order price
            $this->orderTotal += $ticket->getMontant();
            $user->setTotal($this->orderTotal);
            //link tickets to an user
            $ticket->setUser($user);
        }
        return $user;
    }


}