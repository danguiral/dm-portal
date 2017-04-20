<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ArticleType;
use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="get_articles")
     * @Method({"GET"})
     */
    public function getArticlesAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')
            ->findAll();

        return $this->render('AppBundle:Article:get_articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/articles/add", name="post_articles")
     * @Method({"GET", "POST"})
     */
    public function postArticlesAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article->setUser($this->getUser());
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('get_articles');
        }

        return $this->render('AppBundle:Article:post_articles.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
