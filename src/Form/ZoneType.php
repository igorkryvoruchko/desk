<?php

namespace App\Form;

use App\Entity\Restaurant;
use App\Entity\Zone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ZoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('alias', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('restaurant', EntityType::class, [
                'class' => Restaurant::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('translations', CollectionType::class, [
                'entry_type' => ZoneTranslationType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Zone::class,
            'csrf_protection' => false,
        ]);
    }
}
