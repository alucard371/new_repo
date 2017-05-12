<?php

namespace LouvreBundle\Controller;

use LouvreBundle\Entity\Billet;
use LouvreBundle\Entity\User;
use LouvreBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
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

        $billets = $user->getBillets();
        $sum = $user->getTotal($billets);
        $user->setTotal($sum);

        $stripeEmail = $request->request->get("stripeEmail");

        $orderTotal = $user->getTotal($billets);
        $orderDate = new \DateTime('now');
        $user->setOrderDate($orderDate);

        if ($request->isMethod('POST')) {
            try {
                // Use Stripe's library to make requests...
                $token = $request->request->get("stripeToken");
                Stripe::setApiKey("sk_test_xoJY3VdgKKZmLffYjnDD1wiz");
                $customer = \Stripe\Customer::create(array('email' => $stripeEmail, 'source' => $token));
                \Stripe\Charge::create(array('customer' => $customer->id, 'amount' => ($orderTotal * 100), 'currency' => 'eur'));

                $reservation = $user->getOrderDate();
                $em = $this->get('doctrine')->getManager();
                $em->persist($user);
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Commande')
                    ->setFrom('set@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'email/order.html.twig',
                            array(
                                'email' => $user->getEmail(),
                                'total' => $orderTotal,
                                'date'  => $reservation,
                                'order' => $user
                            )
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);
                $request->getSession()->clear();
                $this->generateUrl('Accueil');
                $this->addFlash('payment', 'Votre paiement de ' . $orderTotal . ' euros est accepté.');


            } catch (\Stripe\Error\Card $e) {
                $this->addFlash('fail', 'Votre paiement de ' . $orderTotal . ' euros n\' est pas accepté.');
            }
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
