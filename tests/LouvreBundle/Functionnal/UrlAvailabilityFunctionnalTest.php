<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 08/06/2017
 * Time: 07:49
 */

namespace Tests\LouvreBundle\Functionnal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlAvailabilityFunctionnalTest extends WebTestCase
{


    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful ($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        var_dump($client->getResponse()->isSuccessful());
        var_dump($client->getResponse()->getStatusCode());
        var_dump($client->getResponse()->getContent());

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider ()
    {
        return array(
            array('/'),
            array('/recapitulatif'),
            array('/checkout'),
        );
    }
}