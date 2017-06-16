<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 15/06/2017
 * Time: 09:26
 */

namespace LouvreBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class TropDeTickets
 * @package LouvreBundle\Validators
 */
class TropDeTickets extends Constraint
{
    public $message = "On ne peut pas commander plus de 1000 billets pour cette journée, veuillez selectionner une nouvelle date.";

    /**
     * @return string
     */
    public function validatedBy ()
    {
        return get_class($this).'Validator';
    }
}