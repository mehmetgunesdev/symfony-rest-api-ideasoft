<?php

namespace App\Controller\Rest;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Rest\Route("/products")
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractFOSRestController
{

    /**
     * @Rest\Get ()
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function getList(ProductRepository $productRepository): Response
    {
        return $this->json($productRepository->findAll());
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
        $entity = new Product();
        $form = $this->createForm(ProductType::class, $entity);
        $data = json_decode($request->getContent(),true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
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
