<?php
//estamos un poco enfrascados con un error epor el formPostRepository wiring
namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

   //  /**
   //   * @var FormFactoryInterface
   //   */
   //  private $formFactory;

    public function __construct(
       \Twig_Environment $twig,
       MicroPostRepository $microPostRepository
       //FormFactoryInterface $formFactory
      ) {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        //$this->formFactory = $formFactory;
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

       if ($form-isSubmited() && $form->isValid()) {

       }

       return new Response(
          $this->twig->render('micropost/add.html.twig', 
          ['form' => $form->createView()]
          )
       );
    }
}
