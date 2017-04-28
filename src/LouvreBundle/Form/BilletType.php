<?php

namespace LouvreBundle\Form;

use LouvreBundle\Entity\Billet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
                'years' => range(1950,2050),
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],

            ))
            ->add('dateDeVenue', DateTimeType::class, array(
                'widget' => 'choice',
                'format' => 'dd-MM-yyyy-hh-mm',
                'html5' => true,
                'years' => range(1950, 2050)
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
