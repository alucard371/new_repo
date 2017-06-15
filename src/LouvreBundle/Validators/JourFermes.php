<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 13/06/2017
 * Time: 11:13
 */

namespace LouvreBundle\Validators;

use Symfony\Component\Validator\Constraint;

/**
 * Class JourFermes
 * @package LouvreBundle\Validators
 */
class JourFermes extends Constraint
{
    public $message = "Il est impossible de commander un jour de fermeture.";

    /**
     * Returns the name of the class that validates this constraint.
     *
     * By default, this is the fully qualified name of the constraint class
     * suffixed with "Validator". You can override this method to change that
     * behaviour.
     *
     * @return string
     */
    public function validatedBy ()
    {
        return get_class($this).'Validator';
    }
}