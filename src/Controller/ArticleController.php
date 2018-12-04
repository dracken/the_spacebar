<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    public function homepage()
    {
        
        return new Response('Test Page');
    }
    
    /**
     * 
     * @Route "/Article/{$slug}"
     */
    public function show()
    {
        return $this->render('aricle/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
        ]);
    }
}

?>
