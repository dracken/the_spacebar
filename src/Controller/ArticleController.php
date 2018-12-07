<?php

namespace App\Controller;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\MarkdownHelper;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
//use Michelf\MarkdownInterface;

class ArticleController extends AbstractController
{
    /**
     * @var bool
     * Currently unused:  just showing a controller with a constructor!
     */
    private $isDebug;
    /**
     * @var Client
     */
    private $slack;

    public function __construct(bool $isDebug, Client $slack)
    {
        //dump($isDebug);die;
        $this->isDebug = $isDebug;
        $this->slack = $slack;
    }

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
    #public function show(string $slug, MarkdownInterface $markdown, EntityManagerInterface $em, AdapterInterface $cache)//, MarkdownHelper $markdownHelper, SlackClient\ $slack)
    public function show(string $slug, MarkdownHelper $markdown, EntityManagerInterface $em, AdapterInterface $cache, $isDebug, Client $slack)
    {
        //dump($isDebug);die;

        if ($slug === 'khaaaaaan') {
            //$slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
            $message = $slack->createMessage()
                ->from('Khan')
                ->withIcon(':ghost:')
                ->setText('Ah, Kirk, my old friend...');
            $slack->sendMessage($message);
        }


        /**
         * Database from Chapter 3
         */

        $repository = $em->getRepository(Article::class);
        /** @var Article $article */
        $article = $repository->findOneBy(['slug' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }
        //dump($article);die;
        /**/

        $comments = [
            "This is the first comment, it has little to say.",
            "This is another comment, typically we would have pulled this out of the database.",
            "This is the 3rd comment, finally we have enough to iterate through.",
            
        ];


        return $this->render("article/show.html.twig",[
            /* * /
            'title' => ucwords(str_replace("-", " ", $slug)),
            'slug' => $slug,
            'content' => $articleContent,
            /**/
            'article' => $article,
            'comments' => $comments,
        ]);


    }

    /**
     * @Route("/article/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleRoute($slug, LoggerInterface $logger)
    {

        // TODO - actually heart/unheart the article

        $logger->info("Article is being hearted");

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}

