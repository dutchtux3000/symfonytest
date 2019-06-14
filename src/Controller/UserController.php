<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Form\UserType;
use App\Util\FormErrorToArray;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    use FormErrorToArray;



    /**
     * @Route("/users", name="users", methods={"GET"} )
     */
    public function index(UserRepository $users, SerializerInterface $serializer): Response
    {
        return JsonResponse::fromJsonString($serializer->serialize($users->findAll(), "json", ['enable_max_depth' => true]));
    }

    /**
     * @Route("/users/{user}", name="users_show", requirements={"user"="\d+"}, methods={"GET"} )
     */
    public function show(User $user, SerializerInterface $serializer): Response
    {
        return JsonResponse::fromJsonString($serializer->serialize($user, "json", ['enable_max_depth' => true]));
    }

    /**
     * @Route("/users", name="users_new", methods={"POST"} )
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json($user);
        } else {
            return $this->json($this->getErrorsFromForm($form), 422);
        }

    }

    /**
     * @Route("/users/{user}", name="users_edit", requirements={"user"="\d+"}, methods={"PUT", "POST"} )
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->json($user);
        } else {
            return $this->json($this->getErrorsFromForm($form), 422);
        }
    }

    /**
     * @Route("/users/{user}", name="users_delete", requirements={"user"="\d+"}, methods={"DELETE"} )
     */
    public function delete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->json([]);
    }
}

