<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 04/05/2017
 * Time: 11:52
 */

namespace LouvreBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class modifyOrder
{
    /**
     * @var Session
     */
    private $session;

    /**
     * modifyOrder constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function clearOrder (Request $request)
    {
        $session = $request->getSession();
        $session->remove('order');
        return $this->session->getFlashBag()->add('clear', 'Votre commande à été supprimée.');
    }
}