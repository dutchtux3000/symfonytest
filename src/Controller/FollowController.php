<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class FollowController extends AbstractController
{
    /**
     * @Route("/users/{user}/follow/{follow}", name="user_follow")
     */
    public function follow(User $user, User $follow)
    {

        $user->addFollower($follow);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(true);
    }

    /**
     * @Route("/users/{user}/unfollow/{follow}", name="follow", name="user_unfollow")
     */
    public function unfollow(User $user, User $follow)
    {
        $user->removeFollower($follow);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(true);
    }
}
