<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 09/05/2017
 * Time: 12:27
 */

namespace LouvreBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class finalOrder
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Session
     */
    private $session;

    public function __construct (EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public function saveOrder (Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
        $request->getSession()->clear();
    }
}