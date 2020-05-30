<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}/view", name="my_profile")
     */
    public function index($id)
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['id' => $id]);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
