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
                'html5' => false,
                'attr' => [
                    'class' => 'birthdate',
                    'data-provide' => 'datepicker']
            ))
            ->add('dateDeVenue', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'dateDeVenue',
                    'data-provide' => 'datepicker']
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
