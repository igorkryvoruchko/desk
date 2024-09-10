<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;


class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('alias', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
            ])
            ->add('address', TextType::class)
            ->add('type', TextType::class)
            ->add('translations', CollectionType::class, [
                'entry_type' => RestaurantTranslationType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
            'csrf_protection' => false,
        ]);
    }
}
