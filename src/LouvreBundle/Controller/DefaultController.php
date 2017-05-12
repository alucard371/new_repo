<?php

namespace LouvreBundle\Controller;

use Doctrine\ORM\EntityManager;
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
     * @Route("/", name="home")
     * @Template("index/index.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
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
            return $this->redirectToRoute('recapitulatif');
        }
        dump($user);
        dump($session);
        return array(
            'form' => $form->createView(),
            'user' => $user,
            'billet' => $billet,
        );
    }

    /**
     * @Template("recapitulatif/recapitulatif.html.twig")
     * @param Request $request
     * @Route("/recapitulatif", name="recapitulatif")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
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
                $this->addFlash('payment', 'Votre paiement de ' . $orderTotal . ' euros est accepté.');
                $this->redirectToRoute('home');

            } catch (\Stripe\Error\Card $e) {
                $this->addFlash('fail', 'Votre paiement de ' . $orderTotal . ' euros n\' est pas accepté.');
                // Since it's a decline, \Stripe\Error\Card will be caught
                $body = $e->getJsonBody();
                $err = $body['error'];
                $this->addFlash('error',
                    'Code status : ' . $e->getHttpStatus() . "\n".
                    'Le type d\'erreur est : ' . $err['type'] . "\n".
                    'Le code d\'erreur est : ' . $err['code'] . "\n".
                    'Le message d\'erreur est : ' . $err['message']);

            } catch (\Stripe\Error\RateLimit $e) {
                // Too many requests made to the API too quickly
                print('Too many requests made to the API too quickly');
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
                print ('Invalid parameters were supplied to Stripe\'s API');
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                print('Authentication with Stripe\'s API failed');
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
                print ('Network communication with Stripe failed');
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
                print ('Display a very generic error to the user');
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
                print ('Something else happened, completely unrelated to Stripe');
            }
        }

        $order =$session->get('order');

        return array(
            'order' => $user,
            'sum' => $sum
        );
    }

    /**
     * @Route("/recapitulatif/clearOrder", name="clear_order")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearOrder (Request $request)
    {
        $clearOrder = $this->get('modify_order')->clearOrder($request);
        Return $this->redirectToRoute('home', array('clearOrder', $clearOrder));
    }
}
