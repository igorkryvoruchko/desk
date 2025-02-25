<?php

namespace App\Form;

use App\Entity\KindMenu;
use App\Entity\MenuItem;
use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class MenuItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('alias', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('kindMenu', EntityType::class, [
                'class' => KindMenu::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('price', MoneyType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('specialPrice', MoneyType::class, [])
            ->add('photo', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('translations', CollectionType::class, [
                'entry_type' => MenuItemTranslationType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MenuItem::class,
            'csrf_protection' => false,
        ]);
    }
}
