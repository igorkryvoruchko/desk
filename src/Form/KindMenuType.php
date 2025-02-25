<?php

namespace App\Form;

use App\Entity\KindMenu;
use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\KindMenuTranslationType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class KindMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('alias', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('isActive', CheckboxType::class, [
            ])
            ->add('restaurant', EntityType::class, [
                'class' => Restaurant::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('translations', CollectionType::class, [
                'entry_type' => KindMenuTranslationType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => KindMenu::class,
            'csrf_protection' => false,
        ]);
    }
}
