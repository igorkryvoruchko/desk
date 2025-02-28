<?php

namespace App\Form;

use App\Entity\MenuItem;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('orderedTable', EntityType::class, [
                'class' => Table::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('menuItem', EntityType::class, [
                'class' => MenuItem::class,
                'multiple'  => true,
            ])
            ->add('comment', TextType::class)
            ->add('timeFrom', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('amount', MoneyType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'csrf_protection' => false,
        ]);
    }
}
