<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 01/06/2017
 * Time: 10:25
 */

namespace LouvreBundle\Validators;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class DemiJourneeValidator
 * @package LouvreBundle\Validators
 */
class DemiJourneeValidator extends ConstraintValidator
{
    /**
 *
 * Checks if the passed value is valid.
 *
 * @param mixed $value The value that should be validated
 * @param Constraint $constraint The constraint for the validation
 */
    public function validate ($value, Constraint $constraint)
    {
        $now = new \DateTime();

        $reservation = date('H', $value->getTimeStamp());

        if ($reservation < $now && $reservation > 14)
        {
            $this->context->buildViolation($constraint->message)
                 ->addViolation();
        }
    }


}