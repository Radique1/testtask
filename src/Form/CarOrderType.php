<?php
/*
 * This file is part of Call project.
 * © 2013-2021 404Group
 */

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('carType', ChoiceType::class, ['choices' => ['Car' => 'Car', 'Wagon' => 'Wagon']])
            ->add('engine_type', ChoiceType::class, ['choices' => ['Diesel' => 'Diesel', 'Petrol' => 'Petrol']])
            ->add('engine_volume', ChoiceType::class, ['choices' => ['1400' => '1400', '1800' => '1800']])
            ->add('transmission_type', ChoiceType::class, ['choices' => ['MT' => 'MT', 'AT' => 'AT']])
            ->add('transmission_numberOfGears', ChoiceType::class, ['choices' => ['6' => '6', '8' => '8']])
            ->add('body_type', ChoiceType::class, ['choices' => ['Hatchback' => 'Hatchback', 'Sedan' => 'Sedan']])
            ->add('body_doorCount', ChoiceType::class, ['choices' => ['4' => '4', '2' => '2']])
            ->add('color_type', ChoiceType::class, ['choices' => ['Paint' => 'Paint', 'Vinyl' => 'Vinyl']])
            ->add('color_color', ChoiceType::class, ['choices' => ['Black' => 'Black', 'Red' => 'Red']])
            ->add('interior_type', ChoiceType::class, ['choices' => ['Leather' => 'Leather', 'Textile' => 'Textile']])
            ->add('interior_color', ChoiceType::class, ['choices' => ['Black' => 'Black', 'White' => 'White']])
            ->add('additionalOptions', ChoiceType::class, ['choices' => ['ABS' => 'ABS', 'ESP' => 'ESP', 'GPS' => 'GPS'], 'multiple' => true, 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
