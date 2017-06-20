<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 17/05/2017
 * Time: 14:31
 */

namespace LouvreBundle\Services;



// Entity
use Doctrine\Common\Collections\ArrayCollection;
use LouvreBundle\Entity\User;
use LouvreBundle\Manager\OrderManager;
use Doctrine\ORM\EntityManager;

use LouvreBundle\Form\SearchType;
use LouvreBundle\Form\UserType;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\TwigBundle\TwigEngine;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\OptimisticLockException;
//workflow
use Symfony\Component\Workflow\Workflow;
//Stripe error
use Stripe\Error\Card;


/**
 * Class Order
 * @package LouvreBundle\Services
 */
class Order
{
    /**
     * @var OrderManager
     */
    protected $orderManager;

    /**
     * @var EntityManager
     */
    protected $doctrine;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var FormFactory
     */
    protected $form;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Stripe
     */
    private $stripe;

    /**
     * @var Workflow
     */
    private $workflow;

    /**
     * Commande constructor.
     * @param OrderManager $orderManager
     * @param EntityManager $doctrine
     * @param Session $session
     * @param FormFactory $form
     * @param TwigEngine $templating
     * @param \Swift_Mailer $mailer
     * @param Stripe $stripe
     * @param Workflow $workflow
     */
    public function __construct (
        OrderManager    $orderManager,
        EntityManager   $doctrine,
        Session         $session,
        FormFactory     $form,
        TwigEngine      $templating,
        \Swift_Mailer   $mailer,
        Stripe          $stripe,
        Workflow        $workflow
    )
    {
        $this->orderManager = $orderManager;
        $this->doctrine     = $doctrine;
        $this->session      = $session;
        $this->form         = $form;
        $this->templating   = $templating;
        $this->mailer       = $mailer;
        $this->stripe       = $stripe;
        $this->workflow     = $workflow;
    }



    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormView
     */
    public function beginOrder (Request $request)
    {
        $order = $this->session->get('order');
        $session = new Session();

        dump($session);
        dump($order);

        try {
            if ($this->session->get('order')) {
                $this->session->clear();
                $this->session->getFlashBag()->add(
                    'beware',
                    'La commande n\' est pas vide.'
                );

            }
        } catch (\LogicException $exception) {
            $exception->getMessage();
        }

        if ($order !== null)
        {
            $form = $this->form->create(UserType::class, $order);
            $form->handleRequest($request);
        }
        else
        {
            $order = new User();
            $order->setOrderDate(new \DateTime());
            $form = $this->form->create(UserType::class, $order);
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setNombreBillets(count($order->getBillets()));
            if ( $order->isDemiJournee() === null)
            {
                $order->setDemiJournee(false);
            }

            $data = $form->getData();
            $this->session->set('order', $data);


            //transition for the start phase
            $this->workflow->apply($order, 'start');
            try {
                $response = new RedirectResponse('/recapitulatif');
                $response->send();

            } catch (\InvalidArgumentException $exception) {
                $exception->getMessage();
            }

        }
        return $form->createView();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function recap (Request $request)
    {
        $order = $this->session->get('order');
        $numberTickets = $this->orderManager->getTicketsByDate($order->getDateDeVenue());
        dump($numberTickets);



        if ($order !== null)
        {
            if (($numberTickets + $order->getNombreBillets()) < 1000)
            {
                $this->doctrine->persist($order);
                $this->session->getFlashBag()->add(
                    'success',
                    'Votre commande est prête à être enregistrée'
                );
            }
            else if (  (($numberTickets + $order->getNombreBillets()) > 1000) {
            $this->session->getFlashBag()->add(
                'attention',
                'Le maximum de billets vendu ne peut dépasser 1000 unités.
                             Veuillez sélectionner un autre jour pour votre visite.'
            )
            });

        }
        else
        {
            $response = new RedirectResponse('/');
            $response->send();
            $this->session->getFlashBag()->add('fail', 'Votre commande ne peut être vide.');
        }

        dump(($numberTickets + $order->getNombreBillets()));



        dump($order);

        if ($order === null)
        {
            $this->session->getFlashBag()->add('empty', 'Votre commande ne peut être vide.');
            $response = new RedirectResponse('/');
            $response->send();

        }

        if ($order->getNombreBillets() === 0 ) {

            $this->session->getFlashBag()->add('positiveInt', 'Le nombre de billets dans votre commande doit être positif.');
            $response = new RedirectResponse('/');
            $response->send();
        }

        if ($order->getTotal($order->getBillets()) === 0)
        {
            $this->session->getFlashBag()->add('greaterThan0', 'Le total de votre commande doit être supèrieur à 0.');
            $response = new RedirectResponse('/');
            $response->send();
        }



        if ($request->isMethod('POST')) {
            $token = $request->get('stripeToken');
            dump($token);

            //if the token exists
            if ($token) {
                $order->setValidated(true);
                $order->setOrderNumber(uniqid('order_', true));
                try {
                    $this->stripe->chargeCard(
                        $this->stripe->getApiKey(),
                        $token,
                        $order->getTotal($order->getBillets())
                    );
                    $this->doctrine->flush();
                    $response = new RedirectResponse('checkout');
                    $response->send();
                } catch (Card $exception) {
                    $exception->getMessage();
                    $response = new RedirectResponse('Accueil');
                    $response->send();
                    $this->session->getFlashBag()->add(
                        'stripeError',
                        'Une erreur s\'est produite avec votre carte de paiement.'
                    );
                } catch (\InvalidArgumentException $exception) {
                    $exception->getMessage();
                    $response = new RedirectResponse('Accueil');
                    $response->send();
                    $this->session->getFlashBag()->add(
                        'invalid',
                        'Une erreur s\est produite.'
                    );
                } catch (OptimisticLockException $exception) {
                    $exception->getMessage();
                    $response = new RedirectResponse('Accueil');
                    $response->send();
                    $this->session->getFlashBag()->add(
                        'optimistic',
                        'Une erreur s\'est produite'
                    );
                }
                $this->session->getFlashBag()->add(
                    'payment',
                    'Votre règlement est pris en compte.'
                );
                //transition for the payment phase

                $this->workflow->apply($order, 'payment');
                try {
                    $this->doctrine->remove($order);
                    $response = new RedirectResponse('/checkout');
                    $response->send();

                } catch (\InvalidArgumentException $exception) {
                    $exception->getMessage();
                    $this->session->getFlashBag()->add(
                        'wrong',
                        'Une erreur s\'est produite'
                    );
                }
            } else {
                $this->doctrine->remove($order);
                try {
                    $response = new RedirectResponse('/');
                    $response->send();
                } catch (\InvalidArgumentException $exception) {
                    $exception->getMessage();
                    $this->session->getFlashBag()->add(
                        'invalidArg',
                        'Une erreur s\'est produite'
                    );
                }
                $this->session->getFlashBag()->add(
                    'warning',
                    'Votre commande est annulée car la validation n\'est pas effectuée.'
                );

            }
        }
        return $order;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormView
     */
    public function searchOrder (Request $request)
    {
        $form = $this->form->create(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
            $order = $this->doctrine->getRepository('LouvreBundle:User')
                ->findOneBy(['email'=>$search['email']]);



            if($order)
            {
                $this->session->getFlashBag()->add(
                    'success',
                    'Un nouveau mail vous à été envoyé'
                );
                $mail = \Swift_Message::newInstance()
                    ->setSubject('Commande')
                    ->setFrom('balohe37pro@gmail.com')
                    ->setTo($order->getEmail())
                    ->setBody(
                        $this->templating->render(
                            'email/orderMail.html.twig',
                            [
                                'order' => $order,]
                        ),
                        'text/html'
                    );
                $this->mailer->send($mail);
            }
        }
        return $form->createView();
    }

    public function checkout ()
    {
        $order = $this->session->get('order');
        dump($order);
        if ($order != null) {
            $this->session->clear();
            $this->session->getFlashBag()->add('clearOrder', 'Votre commande est maintenant terminée .');
        }
        else {
            $this->session->getFlashBag()->add('emptyOrder', 'Votre commande est vide.');
            $response = new RedirectResponse('/');
            $response->send();

        }
    }
}