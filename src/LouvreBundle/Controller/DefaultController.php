<?php

namespace LouvreBundle\Controller;

use LouvreBundle\Entity\Billet;
use LouvreBundle\Entity\User;
use LouvreBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Stripe\Stripe;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template("index/index.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $user = new User();
        $billet = new Billet();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user->addBillets($user->getBillets());
            $em->persist($user);
            $em->flush();

        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
            'billet' => $billet,
        );
    }


    /**
     * @Route("/paiement", name="paiement")
     * @Template("stripe/stripe.html.twig")
     */
    public function paiement()
    {
       Stripe::setApiKey("sk_test_xoJY3VdgKKZmLffYjnDD1wiz");

        \Stripe\Charge::retrieve(
            "ch_19srLAFhbxjxMAe04ywUigDU",
            array('api_key' => "sk_test_xoJY3VdgKKZmLffYjnDD1wiz")
        );
    }
}





