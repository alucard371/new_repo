<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 13/06/2017
 * Time: 10:53
 */

namespace LouvreBundle\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class JourFeriesValidator
 * @package LouvreBundle\Validators
 */
class JourFeriesValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate ($value, Constraint $constraint)
    {
        $jourFeries = ['01/05', '01/11', '25/12'];

        $date = date('d/m', $value->getTimeStamp());

        foreach ($jourFeries as $jour)
        {
            if ($date === $jour)
            {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }  
}