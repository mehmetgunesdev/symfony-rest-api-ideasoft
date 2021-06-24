<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;

class OrderType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getProductRepository(): \Doctrine\Persistence\ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customerId')
            ->add('total');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Order::class,
            'csrf_protection' => false
        ));
    }
}
