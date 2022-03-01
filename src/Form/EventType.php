<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('location', 
                EntityType::class, ['class' => Location::class, 
                'choice_label' => 'name'
                ])
            ->add('beginAt')
            ->add('endAt')
            ->add('nbDaysBeforeClosing')
            ->add('inscriptionMax')
            ->add('description')
            ->add('photo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
