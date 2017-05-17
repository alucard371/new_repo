<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 15/05/2017
 * Time: 13:46
 */

namespace LouvreBundle\Listeners;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use LouvreBundle\Entity\User;
use LouvreBundle\Services\Montant;
use Symfony\Bundle\TwigBundle\TwigEngine;

class OrderListener
{
    /**
     * @var Montant
     */
    private $montant;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * OrderListener constructor.
     * @param Montant $montant
     * @param \Swift_Mailer $mailer
     * @param TwigEngine $templating
     * @internal param $Price $
     */
    public function __construct (Montant $montant, \Swift_Mailer $mailer, TwigEngine $templating)
    {
        $this->montant      = $montant;
        $this->mailer       = $mailer;
        $this->templating   = $templating;
    }

    public function prePersist (LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $mail = \Swift_Message::newInstance()
            ->setSubject('Commande')
            ->setFrom('balohe37pro@gmail.com')
            ->setTo($entity->getEmail())
            ->setBody(
                $this->templating->render(
                    'email/order.html.twig',
                    array(
                        'order' => $entity,
                    )
                ),
                'text/html'
            );
        $this->mailer->send($mail);
    }
}