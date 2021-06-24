<?php

namespace App\Controller\Rest;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use DateTime;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Rest\Route("/orders")
 * Class OrderController
 * @package App\Controller
 */
class OrderController extends AbstractFOSRestController
{

    /**
     * @Rest\Get ()
     * @param OrderRepository $repository
     * @return Response
     */
    public function getList(OrderRepository $repository): Response
    {
        return $this->json($repository->findAll());
    }


    /**
     * @Rest\Post ()
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entity = new Order();
        $form = $this->createForm(OrderType::class, $entity);
        $data = json_decode($request->getContent(),true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($data['items'] as &$item) {
                if (!empty($item['productId'])) {
                    $has_stock = $entityManager->getRepository(Product::class)->getStockById($item['productId']);
                    if (empty($has_stock)) {
                        $form->get('items')->addError(new FormError('Product stock has no valid'));
                        return $this->handleView($this->view($form->getErrors()));
                    }
                }
            }
            $entity->setItems($data['items']);
            try {
                $entityManager->persist($entity);
                $entityManager->flush();
            } catch (\Exception $exception) {
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }


}
