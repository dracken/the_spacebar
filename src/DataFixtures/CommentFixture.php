<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{

    private static $commentAuthors = [
        'Captain Kirk',
        'Ambassador Spock',
        'Luke Skywalker',
        'Dark Helmet',
        'President Scroob',
    ];

    public function getDependencies()
    {
        return [ArticleFixtures::class];
    }

    public function loadData(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->createMany(Comment::class, 100, function(Comment $comment) {
            $comment->setContent(
                $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentence(2, true)
            );

            $comment->setAuthorName($this->faker->name);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));
            //$comment->setArticle($this->getReference(Article::class.'_'.$this->faker->numberBetween(0, 9)));
            $comment->setArticle($this->getRandomReference(Article::class));

            $comment->setIsDeleted($this->faker->boolean(20));
        });

        /*
        $comment1 = new Comment();
        $comment1->setAuthorName($this->faker->randomElement(self::$commentAuthors));
        $comment1->setContent('I ate a normal rock once.  It did NOT taste like bacon!');
        $comment1->setArticle($article);
        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setAuthorName('Dracken Firebreather');
        $comment2->setContent('The Bacon flavored Asteroid is a rare and delicious celestial body!');
        $comment2->setArticle($article);
        $manager->persist($comment2);
        */

        //$article->addComment($comment1);
        //$article->addComment($comment2);

        $manager->flush();
    }
}
