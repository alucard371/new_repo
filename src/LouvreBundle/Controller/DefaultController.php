<?php

namespace LouvreBundle\Controller;

use LouvreBundle\Entity\Billet;
use LouvreBundle\Entity\User;
use LouvreBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $session = new Session();
        $user = new User();
        $billet = new Billet();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user->addBillets($user->getBillets());
            $session->set('order', $user );
            return $this->redirectToRoute('Recapitulatif');
        }

        return $this->render('index/index.html.twig',array(
            'form' => $form->createView(),
            'user' => $user,
            'billet' => $billet,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recapAction (Request $request)
    {
        $session = $request->getSession();
        $user = $session->get('order');
        dump($session);
        $billets = $user->getBillets();
        $sum = $user->getTotal($billets);
        $user->setTotal($sum);

        $stripeEmail = $request->request->get("stripeEmail");

        $orderTotal = $user->getTotal($billets);
        $orderDate = new \DateTime('now');
        $user->setOrderDate($orderDate);

        if ($request->isMethod('POST')) {
                $reservation = $user->getOrderDate();
                $em = $this->get('doctrine')->getManager();
                $em->persist($user);
                $em->flush();
                $request->getSession()->clear();
                $this->generateUrl('Accueil');
                $this->addFlash('payment', 'Votre paiement de ' . $orderTotal . ' euros est accepté.');



        }

        return $this->render('recapitulatif/recapitulatif.html.twig',array(
            'order' => $user,
            'sum' => $sum
        ));
    }

    /**
     * @Route("/recapitulatif/clearOrder", name="clear_order")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearOrder (Request $request)
    {
        $clearOrder = $this->get('modify_order')->clearOrder($request);
        Return $this->redirectToRoute('Accueil', array('clearOrder', $clearOrder));
    }
}
