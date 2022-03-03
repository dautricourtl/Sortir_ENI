<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('beginAt', DateTimeType::class, ['widget' => 'single_text'])
            ->add('limitInscriptionAt', DateTimeType::class, ['widget' => 'single_text'])
            ->add('inscriptionMax')
            ->add('duration')
            ->add('description')
            ->add('location', 
                EntityType::class, ['class' => Location::class, 
                'choice_label' => 'name'
                ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '500K',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Téléchargez une image en .jpg ou .png de max 500k uniquement'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
