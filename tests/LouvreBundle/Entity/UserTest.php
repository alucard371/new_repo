<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 02/06/2017
 * Time: 09:28
 */

namespace tests\LouvreBundle\Entity;

use LouvreBundle\Entity\User;
use PHPUnit\Framework\TestCase;


/**
 * Class UserTest
 * @package tests\LouvreBundle\Entity
 */
class UserTest extends TestCase
{
    public function testGetNombreBillets ()
    {
        $user = new User();

        $user->setNombreBillets(6);

        $result = $user->getNombreBillets();

        $this->assertNotNull($result);
    }


    public function testSetDemiJournee ()
    {
        $user = new User();

        $result = $user->setDemiJournee(true);

        $this->assertTrue(true,$result);
    }
}
