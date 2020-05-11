<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
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
     * @Route("/article/{slug}", name="article_view")
     */
    public function article($slug){
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);

//        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy(['articles_id'=> $article['id']]);


        if(!$article){
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        return $this->render('articles/article.html.twig', [
            'article' => $article,
//            'commentaires' => $commentaires,
        ]);
    }

}
