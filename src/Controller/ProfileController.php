<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ModifyUsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ProfileController extends AbstractController
{
    /**
     * @Route ("/profile", name="profile_home")*
     */
    public function index(Request $request, EntityManagerInterface $manager) {

        $user = $this->getUser();

        $form = $this->createForm(ModifyUsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $manager->persist($user);
            $manager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('profile_home');
        }
        //dd($user);
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/profile/{id}/view", name="profile_viewer")
     */
    public function viewprofile($id)
    {
        $profile = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['id' => $id]);

        return $this->render('profile/view.html.twig', [
            'profile' => $profile,
        ]);
    }
}
