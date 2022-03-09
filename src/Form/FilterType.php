<?php

namespace App\Form;

use App\Entity\Site;
use App\data\MyFilterCustom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site', 
            EntityType::class, ['class' => Site::class, 
            'choice_label' => 'name'
            ])
            ->add('name')
            ->add('dateEntre', DateTimeType::class, ['widget' => 'single_text'])
            ->add('dateEt', DateTimeType::class, ['widget' => 'single_text'])
            // ->add('participateEvent', RadioType::class, ['required' => false])
            // ->add('notParticipateEvent', RadioType::class, ['required' => false])
            ->add('organizer', ChoiceType::class, [
                'choices'=> [
                    "Sortie dont je suis l'organisateur/trice" => "Sortie dont je suis l'organisateur/trice"
                ],
            'required' =>false,
            'expanded' => true,
            'multiple'=> true])
            ->add('statePast', ChoiceType::class, [
                'choices'=> [
                    "Sorties passées" => "Sortie(s) passée(s)"
                ],
            'required' =>false,
            'expanded' => true,
            'multiple'=> true])
            ->add('search', SubmitType::class, ['label'=> 'Chercher'])
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MyFilterCustom::class,
        ]);
    }

    

}
