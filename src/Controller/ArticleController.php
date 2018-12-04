<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        
        return new Response('Test Page');
    }
    
    /**
     * @Route("/article/{slug}")
     */
    public function show(string $slug)
    {
        $comments = [
            "This is the first comment, it has little to say.",
            "This is another comment, typically we would have pulled this out of the database.",
            "This is the 3rd comment, finally we have enough to iterate through.",
            
        ];

        return $this->render("article/show.html.twig",[
            'title' => ucwords(str_replace("-", " ", $slug)),
            'comments' => $comments,
        ]);

    }
}

