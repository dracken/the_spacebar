<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        
        return $this->render('article/homepage.html.twig');
    }
    
    /**
     * @Route("/article/{slug}", name="article_show")
     */
    public function show(string $slug, Environment $twigEnvironment)
    {
        $comments = [
            "This is the first comment, it has little to say.",
            "This is another comment, typically we would have pulled this out of the database.",
            "This is the 3rd comment, finally we have enough to iterate through.",
            
        ];

        return $this->render("article/show.html.twig",[
            'title' => ucwords(str_replace("-", " ", $slug)),
            'slug' => $slug,
            'comments' => $comments,
        ]);

    }

    /**
     * @Route("/article/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleRoute($slug, LoggerInterface $logger)
    {

        // TODO - actaully heart/unheart the article

        $logger->info("Article is being hearted");

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}

