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

/**
 * Class Stripe
 * @package LouvreBundle\Services
 */
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
     * @param string $apiKey
     * @param string $apiToken
     */
    public function __construct ($apiKey, $apiToken)
    {
        $this->apiKey   = $apiKey;
        $this->apiToken = $apiToken;
    }

    /**
     * @param string $apiKey
     * @param string $apiToken
     * @param int $total
     * @internal param string $api
     * @internal param string $token
     */
    public function chargeCard ($apiKey, $apiToken, $total)
    {
        \Stripe\Stripe::setApiKey($this->getApiToken());

        try {
            Charge::create([
                'source'        => $apiToken,
                'amount'        => ($total * 100),
                'currency'      => 'eur',
                'description'   => 'Billeterie du Louvre',]);

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