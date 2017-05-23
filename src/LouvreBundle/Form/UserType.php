<?php

namespace LouvreBundle\Form;

use LouvreBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email')
                ->add('dateDeVenue', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr' => [
                    'class'                             => 'dateDeVenue',
                    'data-provide'                      => 'datepicker',
                    'data-date-language'                => 'fr',
                    'data-date-format'                  => 'dd/mm/yyyy',
                    'data-date-days-of-week-disabled'   => '0,',
                    'data-date-start-date'              => '+Od',
                    'data-date-auto-close'              => true,
                    'data-date-clear-btn'               => true,
                    'data-date-today-highlight'         => true,
                    'data-date-dates-disabled'          => '01/05/2017', '25/05/2017', '01/11/2017', '04/06/2017', '05/06/2017', '14/07/2017', '15/08/2017', '11/11/2017', '25/12/2017'
                ]
            ))
            ->add('demiJournee', CheckboxType::class, array(
                'label'     => 'Demi-journée',
                'required'  => false,
            ));


        $builder->add('billets', CollectionType::class, array(
                'entry_type' => BilletType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label'     => ' '
        ));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvrebundle_user';
    }


}
