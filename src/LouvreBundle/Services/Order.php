<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 17/05/2017
 * Time: 14:31
 */

namespace LouvreBundle\Services;

// Entity
use LouvreBundle\Entity\Billet;
use LouvreBundle\Entity\User;

use Doctrine\ORM\EntityManager;
use LouvreBundle\Form\UserType;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\TwigBundle\TwigEngine;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\OptimisticLockException;
//workflow
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Workflow\Exception\LogicException;
//Stripe error
use Stripe\Error\Card;

class Order
{
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
     * @param EntityManager $doctrine
     * @param Session $session
     * @param FormFactory $form
     * @param TwigEngine $templating
     * @param \Swift_Mailer $mailer
     * @param Stripe $stripe
     * @param Workflow $workflow
     */
    public function __construct (
        EntityManager   $doctrine,
        Session         $session,
        FormFactory     $form,
        TwigEngine      $templating,
        \Swift_Mailer   $mailer,
        Stripe          $stripe,
        Workflow        $workflow
    )
    {
        $this->doctrine     = $doctrine;
        $this->session      = $session;
        $this->form         = $form;
        $this->templating   = $templating;
        $this->mailer       = $mailer;
        $this->stripe       = $stripe;
        $this->workflow     = $workflow;
    }

    /**
     * @return int
     */
    public function retrieveBillets ()
    {
        return count(
            $this->doctrine->getRepository(Billet::class)
                            ->getBilletsByDay()
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\FormView
     */
    public function beginOrder (Request $request)
    {
        try {
            if ($this->session->get('order')) {
                $this->session->clear();
                throw new \LogicException(
                    sprintf (
                        'La comande n\'est pas vide !'
                )
                );
            }
        } catch (\LogicException $exception) {
            $exception->getMessage();
        }

        $order = new User();
        $order->setOrderDate(new \DateTime());
        $form = $this->form->create(UserType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->session->set('order', $data);
            $this->session->getFlashBag()->add(
                'success',
                'Votre commande est enregistrée'
            );

            //transition for the start phase
            $this->workflow->apply($order, 'start');
            try {
                $response = new RedirectResponse('/recap');
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
        dump($request);

        if ($order->getBillets() === null || null === $order) {
            throw new \LogicException(
                printf(
                    'la commande est vide'
                )
            );
        }

        try {
            if ($this->retrieveBillets() + count($order->getBillets()) > 1000) {
                $response = new RedirectResponse('/');
                $response->send();
                $this->session->getFlashBag()->add(
                    'warning',
                    'Le maximum de billes vendu ne peu dépasser 1000.'
                );
            }
        } catch (\InvalidArgumentException $exception) {
            $exception->getMessage();
        }

        $this->doctrine->persist($order);

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
                } catch (\InvalidArgumentException $exception) {
                    $exception->getMessage();
                } catch (OptimisticLockException $exception) {
                    $exception->getMessage();
                }
                $this->session->getFlashBag()->add(
                    'success',
                    'Votre commande à bien été enregistrée'
                );
                //transition for the payment phase
                $this->workflow->apply($order, 'payment');
                try {
                    $response = new RedirectResponse('/checkout');
                    $response->send();
                } catch (\InvalidArgumentException $exception) {
                    $exception->getMessage();
                }
            } else {
                $this->doctrine->remove($order);
                try {
                    $response = new RedirectResponse('/');
                    $response->send();
                } catch (\InvalidArgumentException $exception) {
                    $exception->getMessage();
                }
                $this->session->getFlashBag()->add(
                    'warning',
                    'Votre commande est annulée car la validation n\'est pas effectuée.'
                );

            }
        }
        return $order;
    }

}