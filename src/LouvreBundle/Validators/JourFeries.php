<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 13/06/2017
 * Time: 10:49
 */

namespace LouvreBundle\Validators;
use Symfony\Component\Validator\Constraint;

/**
 * Class JourFeries
 * @package LouvreBundle\Validators
 */
class JourFeries extends Constraint
{
    public $message = "On ne peut pas selectionner un jour ferié.";

    /**
     * @return string
     */
    public function ValidateBy ()
    {
        return get_class($this).'Validator';
    }
}