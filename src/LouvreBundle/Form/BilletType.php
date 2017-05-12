<?php

namespace LouvreBundle\Form;

use LouvreBundle\Entity\Billet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('pays')
            ->add('birthdate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr' => [
                    'class'                 => 'birthdate',
                    'data-provide'          => 'datepicker',
                    'data-date-format'      => "dd/mm/yyyy",
                'data-date-language'        => "fr"]

            ))
            ->add('dateDeVenue', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr' => [
                    'class'                             => 'dateDeVenue',
                    'data-provide'                      => 'datepicker',
                    'data-date-language'                => 'fr',
                    'data-date-format'                  => 'dd/mm/yyyy',
                    'data-date-start-date'              => '+Od',
                    'data-date-days-of-week-disabled'   => '0',
                    'data-date-clear-btn'               => true,
                    'data-date-today-highlight'         => true,
                    'data-date-dates-disabled'          => '25/12/2017','01/05/2018', '01/11/2017'
                    ]
            ))
            ->add('heureDeVenue', TimeType::class, array(
                'html5'     => true,
                'input'     => 'datetime',
                'widget'    => 'choice',
                'hours'     => array(
                    '9','10','11','12','13','14','15','16','17','18','19','20','21','22'
                ),
                'attr' => ['class' => 'heureDeVenue']
            ))
            ->add('tarifReduit', CheckboxType::class, array(
                 'label' => 'Tarif réduit ',
                'required' => false,
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Billet::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvrebundle_billet';
    }


}
