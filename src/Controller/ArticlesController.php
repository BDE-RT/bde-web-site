<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use App\Entity\CommentReply;
use App\Form\CommentairesFormType;
//use App\Form\AjoutArticlesFormType;
use App\Form\CommentReplyFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findAll();

        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/{slug}/view", name="article_view")
     */
    public function article($slug, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([
            'articles' => $article,
        ], ['id' => 'desc']);

        if (!$article) {
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        $user = $this->getUser();


        $commentaire = new Commentaires();

        $form = $this->createForm(CommentairesFormType::class, $commentaire);

        $form->handleRequest($request);

//        dd($commentaires);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setArticles($article);
            $commentaire->setCreatedAt(new \DateTime('now'));
            $commentaire->setUsername($user);
            $commentaire->setUsersId($user);

            $doctrine = $this->getDoctrine()->getManager();

            $doctrine->persist($commentaire);

            $doctrine->flush();
            $this->addFlash('success', 'Commentaire envoyé');
            return $this->redirectToRoute('article_view', ['slug' => $slug]);
        }


        return $this->render('articles/article.html.twig', [
            'article' => $article,
            'formComment' => $form->createView(),
            'commentaires' => $commentaires,
        ]);
    }

    /**
     * @Route("/articles/{slug}/reply/{id}", name="reply")
     */
    public function reply($id, $slug, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $commentaire = $this->getDoctrine()->getRepository(Commentaires::class)->findOneBy(['id' => $id]);

        $reply = new CommentReply();
        $formReply = $this->createForm(CommentReplyFormType::class, $reply);

        $formReply->handleRequest($request);

        $user = $this->getUser();

        if ($formReply->isSubmitted() && $formReply->isValid()) {
            $reply->setCommentaire($commentaire);
            $reply->setCreatedAt(new \DateTime('now'));
            $reply->setUsername($user);
            $reply->setUsersId($user);

            $doctrine = $this->getDoctrine()->getManager();

            $doctrine->persist($reply);

            $doctrine->flush();
            $this->addFlash('success', 'Reponse au commentaire envoyé');
            return $this->redirectToRoute('article_view', ['slug' => $slug]);
        }


        return $this->render('articles/reply.html.twig', [
            'commentaire' => $commentaire,
            'formReply' => $formReply->createView(),
            'article' => $article,
        ]);
    }
}
