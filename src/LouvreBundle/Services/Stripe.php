<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 15/05/2017
 * Time: 10:25
 */

namespace LouvreBundle\Services;


use Stripe\Charge;
use Stripe\Error\Card;

class Stripe
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiToken;

    /**
     * Stripe constructor.
     * @param $apiKey
     * @param $apiToken
     */
    public function __construct ($apiKey, $apiToken)
    {
        $this->apiKey   = $apiKey;
        $this->apiToken = $apiToken;
    }

    /**
     * @param string $api
     * @param string $token
     * @param int $total
     */
    public function chargeCard ($api, $token, $total)
    {
        \Stripe\Stripe::setApiKey($api);

        try {
            Charge::create(array(
                'source'        => $token,
                'amount'        => ($total * 100),
                'currency'      => 'eur',
                'description'   => 'Billeterie du Louvre',));

        } catch (Card $e) {
            $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getApiKey ()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getApiToken ()
    {
        return $this->apiToken;
    }
}