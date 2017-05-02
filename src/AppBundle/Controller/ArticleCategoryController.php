<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ArticleCategory;
use AppBundle\Form\Type\ArticleCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ArticleCategoryController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/categories", name="get_categories")
     * @Method({"GET"})
     * @return Response
     */
    public function getCategoriesAction(): Response
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:ArticleCategory')
            ->findAll();

        return $this->render('AppBundle:Article:get_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/categories/add", name="post_categories")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function postCategoriesAction(Request $request): Response
    {
        $category = new ArticleCategory();
        $form = $this->createForm(ArticleCategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('get_categories');
        }

        return $this->render('AppBundle:Article:post_categories.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/categories/{id}/remove", name="delete_category")
     * @Method({"GET"})
     * @param int $id
     * @return Response
     */
    public function deleteCategoryAction(int $id): Response
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:ArticleCategory')
            ->find($id);

        if (!$category) {
            $this->CategoryNotFound();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('get_categories');
    }

    /**
     * @return NotFoundHttpException
     */
    private function categoryNotFound(): NotFoundHttpException
    {
        throw new NotFoundHttpException('Category not found.');
    }
}
