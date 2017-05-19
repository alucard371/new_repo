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

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexOrderAction (Request $request)
    {
        $billet = $this->get('order')->retrieveBillets();

        $form = $this->get('order')->beginOrder($request);

        return $this->render('index/indexOrder.html.twig', [
           'billet' => $billet,
            'form'  => $form,
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

        $key = $this->get('stripe')->getApiToken();

        return $this->render(':recapitulatif:recapOrder.html.twig', [
            'order' => $recap,
            'key' => $key,
        ]);
    }

    public function checkoutAction ()
    {
        return $this->render('checkout/checkout.html.twig');
    }


}