<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Suggestion;
use AppBundle\Form\Type\SuggestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SuggestionController extends Controller
{

    /**
     * @Route("/suggestions", name="get_suggestions")
     * @Method({"GET"})
     * @return Response
     */
    public function getSuggestionsAction(): Response
    {
        $suggestions = $this->getDoctrine()->getManager()->getRepository(Suggestion::class)->findAll();

        return $this->render('AppBundle:Suggestion:get_suggestions.html.twig', ['suggestions' => $suggestions]);
    }

    /**
     * @Route("/suggestions/add", name="post_suggestions")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function postSuggestionsAction(Request $request): Response
    {
        $suggestion = new Suggestion();

        $form = $this->createForm(SuggestionType::class, $suggestion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $suggestion->getFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('suggestion_directory'),
                $fileName
            );
            $suggestion->setFile($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($suggestion);
            $em->flush();

            return $this->redirect($this->generateUrl('get_suggestions'));
        }

        return $this->render('AppBundle:Suggestion:post_suggestions.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/suggestions/download/{file}",name="get_suggestions_download")
     */
    public function getSuggestionsDownloadAction($file) {

        $repository = $this->getDoctrine()->getManager()->getRepository(Suggestion::class);
        $suggestion = $repository->findOneBy(['file' => $file]);

        if(!$suggestion) {
            throw new Exception('File not found');
        }
        $filename = $suggestion->getFile();

        $response = new Response();
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename ));
        $response->setContent(file_get_contents($this->getParameter('suggestion_directory').'/'.$filename));
        $response->setStatusCode(200);
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}
