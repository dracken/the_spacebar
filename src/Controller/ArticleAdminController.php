<?php

namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleAdminController
 * @package App\Controller
 */
class ArticleAdminController extends AbstractController
{

    /**
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     * @Route("/admin/article/new", name="app_articleadmin_new")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //die('todo');

        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData());

            $datePublished  = new \DateTime('@'.strtotime('now'));

            /** @var Article $article */
            $article = $form->getData();
            /*
            $data = $form->getData();
            dd($data);
            $article = new Article();
            $article->SetTitle($data['title']);
            $article->setContent($data['content']);
            */
            $article->setAuthor($this->getUser());
            $article->setImage('mercury.jpeg');
            $article->setPublished(true);
            $article->setPublishedAt($datePublished);

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article Created!');

            return $this->redirectToRoute('app_articleadmin_list');
        }

        return $this->render('article_admin/new.html.twig',
            [
                'articleForm' => $form->createView(),
            ]);
    /*
        return new Response
        $article = new Article();

        $article->setTitle('Why Asteroids Taste Like Bacon')
            ->setSlug('light-speed-travel-fountain-of-youth-or-fallacy')//.rand(100,999))
            ->setContent(<<<EOF
__light-speed-travel-fountain-of-youth-or-fallacy__

Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
**turkey** shank eu pork belly meatball non cupim.

Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak.

Meatball adipisicing ribeye bacon ***strip steak*** eu. Consectetur ham hock pork hamburger enim strip steak
mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
fugiat.
EOF
            );


        if (rand(1, 10) > 2) {
            $article->setPublishedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $em->persist($article);
        $em->flush();

        return new Response(sprintf('New Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    */
    }

    /**
     * @Route("/admin/article/{id}/edit", name="app_articleadmin_edit")
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return mixed
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArticleFormType::class, $article, [
            'include_published_at' => true
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article updated!  Return to <a href="/admin/article/" class="flash-url"> article list</a>');

            return $this->redirectToRoute('app_articleadmin_edit', [
                'id' => $article->getId(),
            ]);
        }
        return $this->render('article_admin/edit.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article", name="app_articleadmin_list")
     * @param ArticleRepository $articleRepo
     * @return Response
     */
    public function list(ArticleRepository $articleRepo)
    {
        $articles = $articleRepo->findAll();

        return $this->render('article_admin/list.html.twig', [
            'articles' => $articles,
        ]);
    }
}