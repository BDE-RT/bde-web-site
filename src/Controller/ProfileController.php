<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ModifyUsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}/view", name="my_profile")
     */
    public function index($id, Request $request)
    {
        $profile = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['id' => $id]);

        $user = $this->getUser();

        $form = $this->createForm(ModifyUsersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('modify_users')['description'] == ''):
                $user->setDescription($profile->getDescription());
            else:
                $user->setDescription($request->request->get('modify_users')['description']);
            endif;
            $user->setDiscordTag($request->request->get('modify_users')['discordTag']);
            $user->setSteamId($request->request->get('modify_users')['steamId']);
            $user->setFriendCode($request->request->get('modify_users')['friendCode']);
            $user->setBattleTag($request->request->get('modify_users')['battleTag']);
            $user->setXboxname($request->request->get('modify_users')['xboxname']);
            $user->setPalystation($request->request->get('modify_users')['palystation']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('my_profile', ['id' => $id]);
        }

        return $this->render('profile/index.html.twig', [
            'profile' => $profile,
            'form' => $form->createView()
        ]);
    }
}
