<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ArticleType;
use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="get_articles")
     * @Method({"GET"})
     * @return Response
     */
    public function getArticlesAction(): Response
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
     * @param Request $request
     * @return Response
     */
    public function postArticlesAction(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article->setUser($this->getUser());
            $em->persist($article);
            $em->flush();

            // TODO: Send a mail to moderators
            return $this->redirectToRoute('get_articles');
        }

        return $this->render('AppBundle:Article:post_articles.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/articles/{id}", name="get_article")
     * @Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function getArticleAction(int $id): Response
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')
            ->find($id);

        if (!$article) {
            $this->articleNotFound();
        }

        return $this->render('AppBundle:Article:get_article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @return NotFoundHttpException
     */
    private function articleNotFound(): NotFoundHttpException
    {
        throw new NotFoundHttpException('Article not found.');
    }
}
