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
     * @param $apiKey
     * @param $apiToken
     * @param int $total
     * @internal param string $api
     * @internal param string $token
     */
    public function chargeCard ($apiKey, $apiToken, $total)
    {
        \Stripe\Stripe::setApiKey("sk_test_zpjn5Lk4eOaCDzPRaslVL3ft");

        try {
            Charge::create(array(
                'source'        => $apiToken,
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