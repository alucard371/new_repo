<?php

namespace LouvreBundle\Form;

use LouvreBundle\Entity\Billet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Class BilletType
 * @package LouvreBundle\Form
 */
class BilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nom', TextType::class,
                [
                    'constraints' =>
                    [
                        new NotBlank(),
                        new Type('string'),
                        new Length([
                            'min' => 3,
                            'max' => 20,
                            'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères.',
                            'maxMessage' => 'Votre nom ne doit pas contenir plus de  {{ limit }} caractères.'
                        ])
                    ]
                ])
            ->add('prenom', TextType::class,
                [
                    'constraints' =>
                        [
                            new NotBlank(),
                            new Type('string'),
                            new Length([
                                'min' => 3,
                                'max' => 20,
                                'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères.',
                                'maxMessage' => 'Votre nom ne doit pas contenir plus de  {{ limit }} caractères.'
                            ])
                        ]
                ])
            ->add('pays', CountryType::class,
                [
                    'constraints' =>
                    [
                        new Country([
                            'message' => 'La valeur selectionnée est invalide.'
                        ])
                    ]
                ])
            ->add('birthdate', DateType::class, [
                'constraints' => [
                  new NotBlank(),
                ],
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr' => [
                    'class'                 => 'birthdate',
                    'data-provide'          => 'datepicker',
                    'data-date-format'      => "dd/mm/yyyy",
                    'data-date-auto-close'  => true,
                    'data-date-end-date'    => '0d',
                    'data-date-language'    => "fr"]])
            ->add('tarifReduit', CheckboxType::class, [
                 'label' => 'Tarif réduit ',
                'required' => false,]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Billet::class,]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvrebundle_billet';
    }


}
