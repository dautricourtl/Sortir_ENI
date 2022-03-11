<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                        'maxSize' => '1024K',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Téléchargez une image en .jpg ou .png de max 1024K uniquement'
                    ])
                ]
            ])
            ->add('privateEvent', ChoiceType::class,['choices'=>['privé'=>true,'public'=>false],'expanded'=>true])
            ->add('save', SubmitType::class, ['label'=> 'Enregistrer'])
            ->add('addToWhiteList', SubmitType::class, ['label'=>'Ajouter des invités'])
            ->add('publish', SubmitType::class, ['label'=> 'Publier'])
            ->getForm()
        ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
