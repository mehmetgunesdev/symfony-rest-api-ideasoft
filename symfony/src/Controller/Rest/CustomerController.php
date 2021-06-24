<?php

namespace App\Controller\Rest;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use DateTime;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Rest\Route("/customer")
 * Class CustomerController
 * @package App\Controller
 */
class CustomerController extends AbstractFOSRestController
{

    /**
     * @Rest\Get ()
     * @param CustomerRepository $repository
     * @return Response
     */
    public function getList(CustomerRepository $repository): Response
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
        $entity = new Customer();
        $form = $this->createForm(CustomerType::class, $entity);
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
