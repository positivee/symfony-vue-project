<?php

namespace App\Controller;

use App\Entity\FormData;
use App\Form\FormDataType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FromDataController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/forms', name: 'forms', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $repository = $this->em->getRepository(FormData::class);
        $forms = $repository->findAll();

        $data = [];

        foreach ($forms as $form) {
            $data[] = [
                'id' => $form->getId(),
                'name' => $form->getName(),
                'surname' => $form->getSurname(),
                'message' => $form->getMessage(),
                'email' => $form->getEmail(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/forms/create', name: 'forms_create', methods: ['POST'])]
    public function addForm(Request $request): JsonResponse
    {
        $formData = new FormData();
        $form = $this->createForm(FormDataType::class, $formData);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);



        if ($form->isSubmitted() && $form->isValid()) {
            $newFormData = $form->getData();

            $this->em->persist($newFormData);
            $this->em->flush();

            return $this->json('Form succesfully added!', 200);
        } else {

            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                if ($error->getOrigin()) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }
            }

            return $this->json($errors, 400);
        }
    }
}
