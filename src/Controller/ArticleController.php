<?php

namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
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

    public function __construct(bool $isDebug)
    {
        //dump($isDebug);die;
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        /*$repository = $em->getRepository(Article::class);
        $articles = $repository->findBy(['published' => '1'], ['publishedAt' => 'DESC']);# old, busted
        */
        $articles = $repository->findAllPublishedOrderedByNewest();# new hotness
        //dump($repository);die;

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article", name="article")
     */
    public function show_articles(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Article::class);
        /** @var Article $article */
        $article = $repository->findAll();

        return $this->render("article/list.html.twig", [
            'article' => $article,
        ]);
    }
    
    /**
     * @Route("/article/{slug}", name="article_show")
     */
    public function show(Article $article, SlackClient $slack, $isDebug)
    {
        //dump($isDebug);die;

        /*
         * Slack Message Catch
         */
        if ($article->getSlug() === 'khaaaaaan') {
            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');

            return $this->render("article/show.html.twig", [
                'article' => ['title' => "Khaaaaaan",
                    "content" => "Khaaaaaan",
                    "slug" => $slug
                    ],
                'comments' => []
            ]);
        }


        /**
         * Database from Chapter 3
         */

        //$repository = $em->getRepository(Article::class);
        /** @var Article $article */
        //$article = $repository->findOneBy(['slug' => $slug]);

        /*
         * Throw a 404 error if slug isn't found
         * /
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
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
    {

        /*
        $article->setLikes($article->getLikes() + 1);
        $em->flush();
        */
        $article->incrementLikeCount();

        $logger->info("Article is being hearted");

        return new JsonResponse(['hearts' => $article->getLikes()]);
    }
}

