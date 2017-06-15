<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 13/06/2017
 * Time: 11:16
 */

namespace LouvreBundle\Validators;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class JourFermesValidator
 * @package LouvreBundle\Validators
 */
class JourFermesValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate ($value, Constraint $constraint)
    {
        $joursFermes = ['Tue', 'Sun'];

        $day = date('D', $value->getTimeStamp());

        foreach ($joursFermes as $fermes)
        {
            if ($day === $fermes)
            {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}