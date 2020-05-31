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
     * @Route ("/profile", name="profile_home")
     */
    public function index(Request $request) {

        $user = $this->getUser();

        $form = $this->createForm(ModifyUsersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dump($request->request->get('modify_users'));
//            die();
            if ($request->request->get('modify_users')['description'] == ''):
                $user->setDescription($user->getDescription());
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
            return $this->redirectToRoute('profile_home');
        }
        return $this->render('profile/index.html.twig', [
            'profile' => $user,
            'form' => $form->createView()
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
