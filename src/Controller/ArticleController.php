<?php

namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
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
use Symfony\Component\HttpFoundation\Request;

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
    public function homepage(ArticleRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        /*$repository = $em->getRepository(Article::class);
        $articles = $repository->findBy(['published' => '1'], ['publishedAt' => 'DESC']);# old, busted
        * /
        $articles = $repository->findAllPublishedOrderedByNewest();# new hotness
        //dump($repository);die;

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
        /* */

        $articles = $repository->findAllPublishedOrderedByNewest();

        $pagination = $paginator->paginate(
            $articles,
            $request->query->getInt('page', 1), #page number
            5 #limit per page

        );

        return $this->render('article/homepage.html.twig', [
            'pagination' => $pagination,
        ]);
        /* */
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


        return $this->render("article/show.html.twig",[
            /* * /
            'title' => ucwords(str_replace("-", " ", $slug)),
            'slug' => $slug,
            'content' => $articleContent,
            /**/
            'article' => $article,
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

