<?php
//estamos un poco enfrascados con un error epor el formPostRepository wiring
namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/micro-post")
 */

class MicroPostController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(
       \Twig_Environment $twig,
       MicroPostRepository $microPostRepository,
       FormFactoryInterface $formFactory, 
       EntityManagerInterface $entityManager, 
       RouterInterface $router
      ) {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index()
    {
        $html = $this->twig->render('micropost/index.html.twig', [
         'posts' => $this->microPostRepository->findAll()
      ]);
        return new Response($html);
    }

    /**
     * @Route("/add", name="micro_post_add")
     */
    public function add(Request $request)
    {
       $microPost = new MicroPost();
       $microPost->setTime(new \DateTime());
       
       $form = $this->formFactory->create(MicroPostType::class, $microPost);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
         $this->entityManager->persist($microPost);
         $this->entityManager->flush();

         return new RedirectResponse(
            $this->router->generate('micro_post_index')
         );
       }

       return new Response(
          $this->twig->render('micropost/add.html.twig', 
          ['form' => $form->createView()]
          )
       );
    }
}
