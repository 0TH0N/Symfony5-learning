<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ConferenceController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * ConferenceController constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {

        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage")
     *
     * @param ConferenceRepository $conferenceRepository
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(ConferenceRepository $conferenceRepository, SessionInterface $session)
    {
        dump($GLOBALS);
        return new Response($this->twig->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ]));
    }

    /**
     * @Route("/conference/{id}", name="conference")
     *
     * @param Request           $request
     * @param Conference        $conference
     * @param CommentRepository $commentRepository
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(Request $request, Conference $conference, CommentRepository $commentRepository)
    {
        $offset = max(0, $request->query->getInt('offset'), 0);
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments'   => $paginator,
            'previous'   => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next'       => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]));
    }

    /**
     * @Route("/hello/{name}", name="hello")
     * @param string $name
     *
     * @return Response
     */
    public function hello(string $name)
    {
        $greet = $name ? sprintf('<h1>Hello %s!</h1>', htmlspecialchars($name)) : '';

        return new Response(<<<EOF
<html>
    <body>
        $greet
        <img src="/images/under-construction.gif" />
    </body>
</html>
EOF
        );
    }
}
