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
     * @param $mail
     * @return User|null|object
     */
    public function getOrderByMail ($mail)
    {
        $orderByName = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $mail]);

        return $orderByName;
    }

    public function postOrder ()
    {
        $order = new User();

        $this->doctrine->persist($order);
        $this->doctrine->flush();
    }

}