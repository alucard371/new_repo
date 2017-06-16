<?php


namespace LouvreBundle\Services;

/**
 * Created by PhpStorm.
 * User: moi
 * Date: 28/04/2017
 * Time: 11:28
 */
use LouvreBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use LouvreBundle\Form\UserType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AddTickets
 * @package LouvreBundle\Services
 */
class AddTickets
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FormFactory
     */
    private $form;

    /**
     * @var Session
     */
    private $session;

    /**
     * AddTickets constructor.
     * @param EntityManager $em
     * @param FormFactory $form
     * @param Session $session
     */
    public function __construct (EntityManager $em, FormFactory $form, Session $session)
    {
        $this->em =$em;
        $this->form = $form;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormInterface
     */
    public function formBuilder (Request $request)
    {
        $user = new User();

        $form = $this->form->create(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user->addBillets($user->getBillets());
            $this->session->getFlashBag()->add('okay', 'Votre commande à bien été envoyée');
            $em->persist($user);
            $em->flush();

        }
        return $form;

    }
}