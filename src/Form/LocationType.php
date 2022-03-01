<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name',TextType::Class)
            ->add('adress',TextType::Class)
            ->add('latitude',TextType::Class)
            ->add('longitude',TextType::Class)
            ->add('city',EntityType::Class , [
                'class' => City::class,
                'choice_label' => 'displayName',
            ])
            ->add('submit',SubmitType::Class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
