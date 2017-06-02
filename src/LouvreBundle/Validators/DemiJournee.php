<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 01/06/2017
 * Time: 10:20
 */

namespace LouvreBundle\Validators;
use Symfony\Component\Validator\Constraint;

/**
 * Class DemiJournee
 * @package LouvreBundle\Validators
 */
class DemiJournee extends Constraint
{

    /**
     * @var string
     */
    public $message = "Il est impossible de choisir le tarif journée après 14h !";

    /**
     * @return string
     */
    public function ValidateBy ()
    {
        return get_class($this).'Validator';
    }
}