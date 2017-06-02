<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 02/06/2017
 * Time: 10:00
 */

namespace tests\LouvreBundle\Entity;

use LouvreBundle\Entity\Billet;
use PHPUnit\Framework\TestCase;

/**
 * Class BilletTest
 * @package tests\LouvreBundle\Entity
 */
class BilletTest extends TestCase
{
    /**
     * @return int
     */
    public function testSetTarif ()
    {
        $billet = new Billet();

        $tarif = $billet->setTarif(18,true);

        $this->assertEquals("réduit", $tarif);

        return $tarif;
    }

    /**
     * @depends testSetTarif
     */
    public function testSetMontant ($tarif)
    {
        $billet = new Billet();

        $result = $billet->SetMontant($tarif);

        $this->assertEquals(10, $result);
    }


}
