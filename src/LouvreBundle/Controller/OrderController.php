<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 17/05/2017
 * Time: 17:04
 */

namespace LouvreBundle\Controller;

use Doctrine\ORM\ORMInvalidArgumentException;
use Stripe\Error\Card;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OrderController
 * @package LouvreBundle\Controller
 */
class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexOrderAction (Request $request)
    {
        $orders = $this->get('order_manager')->getOrders();

        $form = $this->get('order')->beginOrder($request);
        dump($form);

        $order = $this->get('order')->searchOrder($request);

        $nombreTickets = $this->get('order_manager')->getTicketsByDate(new \DateTime());

        return $this->render('index/indexOrder.html.twig', [
            'tickets'   => $nombreTickets,
            'orders'    => $orders,
            'form'      => $form,
            'order'     => $order
        ]);
    }

    /**
     * @throws \LogicException
     * @throws ORMInvalidArgumentException
     * @throws Card
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recapOrderAction(Request $request)
    {
        $recap = $this->get('order')->recap($request);

        $nombreTickets =  $this->get('order_manager')->getTicketsByDate($recap->getDateDeVenue());

        $key = $this->get('stripe')->getApiKey();

        return $this->render(':recapitulatif:recapOrder.html.twig', [
            'nombreTickets' => $nombreTickets,
            'order' => $recap,
            'key' => $key,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction ()
    {
        return $this->render('checkout/checkout.html.twig');
    }

}