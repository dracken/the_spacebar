<?php

namespace Tests\AppBundle\Entity;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    /* * /// Just test example to show how test cases work.  All test cases must extend TestCase, class must end with Test in the name and all methods inside must begin with test at the front of the function name
    public function testThatYourComputerWorks()
    {
        $this->assertTrue(false);
    }
    /* */

    public function testLikesCount()
    {
        $article = new Article();

        $this->assertSame(0, $article->getLikes());
        // same as $this->assertTrue(0 === $article->getLikes());, but with better error messages

        $article->setLikes(9);
        $this->assertSame(9, $article->getLikes());
    }

    public function testLikesHaveNotShrunk()
    {
        $article = new Article();

        $article->setLikes(15);
        $this->assertGreaterThanOrEqual(14, $article->getLikes(), 'Did you not like the article anymore?');
    }

    public function testReturnsFullArticle()
    {
        $article = new Article();

        $this->assertSame(
          'The author unknown_author of the article \'unknown_article \' was published on unknown_published_date.',
          $article->getAuthor()
        );
    }
}