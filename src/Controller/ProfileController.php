<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{username}/view", name="profile")
     */
    public function index($username)
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['username' => $username]);
//        $profile = $this->getDoctrine()->getRepository()

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
