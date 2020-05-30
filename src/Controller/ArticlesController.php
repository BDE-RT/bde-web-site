<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use App\Form\CommentairesFormType;
use App\Form\AjoutArticlesFormType;
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
    public function article($slug, Request $request){
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([
            'articles' => $article,
        ],['id' => 'desc']);

        if(!$article){
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        $user = $this->getUser();

        $commentaire = new Commentaires();

        $form = $this->createForm(CommentairesFormType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setArticles($article);
            $commentaire->setCreatedAt(new \DateTime('now'));
            $commentaire->setUsername($user);

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
}
