<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('revenue');

        $builder->add(
            'since',
            DateType::class,
            array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                "data" => new \DateTime()
            )
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'csrf_protection'=> false
        ]);
    }
}
