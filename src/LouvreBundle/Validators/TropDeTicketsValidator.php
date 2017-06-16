<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 15/06/2017
 * Time: 09:29
 */

namespace LouvreBundle\Validators;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use LouvreBundle\Manager\OrderManager;

/**
 * Class TropDeTicketsValidator
 * @package LouvreBundle\Validators
 */
class TropDeTicketsValidator extends ConstraintValidator
{
    private $orderManager;

    /**
     * TropDeTicketsValidator constructor.
     * @param OrderManager $orderManager
     */
    public function __construct (OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate ($value, Constraint $constraint)
    {
        $date = date('Y-m-d', $value->getTimeStamp());

        $datetime = new \DateTime($date);

        $tickets = $this->orderManager->getTicketsByDate($datetime);

        if ((1000 - $tickets) <= 0)
        {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}