<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class ArticleController
{
    public function homepage()
    {
        
        return new Response('Test Page');
    }
    
    public function show()
    {
        return new Response('Future page to show one space article.');
    }
}

?>
