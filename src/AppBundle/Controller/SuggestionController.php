<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Suggestion;
use AppBundle\Entity\SuggestionStatus;
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
            /** @var Suggestion $suggestion */
            $status = $this->get('query_service')->findOneOrException(SuggestionStatus::class, ['id' => 1]);

            /**@var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $suggestion->getFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('suggestion_directory'),
                $fileName
            );
            $suggestion->setFile($fileName);
            $suggestion->setUser($this->getUser());
            $suggestion->setStatus($status);
            $em = $this->getDoctrine()->getManager();
            $em->persist($suggestion);
            $em->flush();

            return $this->redirect($this->generateUrl('get_suggestions'));
        }

        return $this->render('AppBundle:Suggestion:post_suggestions.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/suggestions/{id}", name="get_suggestion")
     * @Method({"GET"})
     * @return Response
     */
    public function getSuggestionAction($id): Response
    {
        /** @var Suggestion $suggestion */
        $suggestion = $this->get('query_service')->findOneOrException(Suggestion::class, ['id' => $id]);

        if ($this->getUser()->isAdmin() && $suggestion->getStatus()->getId() == 1) {
            $suggestionstatus = $this->get('query_service')->findOneOrException(SuggestionStatus::class, ['id' => 2]);
            $suggestion->setStatus($suggestionstatus);
            $this->get('query_service')->save($suggestion);
        }

        return $this->render('AppBundle:Suggestion:get_suggestion.html.twig', ['suggestion' => $suggestion]);
    }

    /**
     * @Route("/suggestions/download/{file}",name="get_suggestions_download")
     * @Method({"GET"})
     * @return Response
     */
    public function getSuggestionsDownloadAction($file)
    {
        /** @var Suggestion $suggestion */
        $suggestion = $this->get('query_service')->findOneOrException(Suggestion::class, ['file' => $file]);

        $filename = $suggestion->getFile();

        $response = new Response();
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
        $response->setContent(file_get_contents($this->getParameter('suggestion_directory') . '/' . $filename));
        $response->setStatusCode(200);
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    /**
     * @Route("/suggestions/mark_as_used/{id}", name="get_suggestions_mark_as_used")
     * @Method({"GET"})
     */
    public function getSuggestionsMarkAsUsedAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Permission denied');
        $suggestion = $this->get('query_service')->findOneOrException(Suggestion::class, ['id' => $id]);
        $suggestionstatus = $this->get('query_service')->findOneOrException(SuggestionStatus::class, ['id' => 3]);
        $suggestion->setStatus($suggestionstatus);
        $this->get('query_service')->save($suggestion);

        return $this->redirectToRoute('get_suggestion', ['id' => $id]);
    }
}
