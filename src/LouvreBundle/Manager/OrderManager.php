<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 19/05/2017
 * Time: 12:22
 */

namespace LouvreBundle\Manager;

use Doctrine\ORM\EntityManager;
use LouvreBundle\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class OrderManager
 * @package LouvreBundle\Manager
 */
class OrderManager
{
    /**
     * @var EntityManager
     */
    private $doctrine;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * OrderManager constructor.
     * @param EntityManager $doctrine
     * @param RequestStack $request
     */
    public function __construct (EntityManager $doctrine, RequestStack $request)
{
    $this->doctrine = $doctrine;
    $this->request  = $request;
}

    /**
     * @return array|User[]
     */
    public function getOrders ()
    {
        $orders = $this->doctrine->getRepository(User::class)->findAll();

        return $orders;
    }

    /**
     * @param $visitDate
     * @return array|User[]
     * @internal param $visitDate
     */
    public function getOrdersByDate ($visitDate)
    {
        $orders = $this->doctrine->getRepository(User::class)->findBy(['dateDeVenue' => $visitDate]);

        return $orders;
    }

    /**
     * @param $date
     * @return int
     */
    public function getTicketsByDate ($date)
    {
        $orders = $this->doctrine->getRepository(User::class)->findBy(['dateDeVenue' => $date]);
        $nombreTickets = 0;

        foreach ($orders as $order)
        {
            $nombreTickets += $order->getNombreBillets() ;
        }

        return $nombreTickets;
    }

    /**
     * @param $mail
     * @return User|null|object
     */
    public function getOrderByMail ($mail)
    {
        $orderByMail = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $mail]);
        return $orderByMail;
    }

    public function postOrder ()
    {
        $order = new User();

        $this->doctrine->persist($order);
        $this->doctrine->flush();
    }

}