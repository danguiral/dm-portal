<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class ArticleControllerTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
        \Tests\AppBundle\Utils\Database::prepareDb($this->client);
    }
    
    /*
     * GET /articles
     */
    public function testShowArticles()
    {
        \Tests\AppBundle\Utils\Auth::logIn($this->client);

        $crawler = $this->client->request('GET', '/articles');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Articles', $crawler->filter('h2')->text());
    }

    /*
     * GET /article/6
     */
    public function testShowArticle()
    {
        \Tests\AppBundle\Utils\Auth::logIn($this->client);

        $crawler = $this->client->request('GET', '/articles/6');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Le super article de Xu', $crawler->filter('h1')->text());
    }

    /*
     * POST /articles/add
     */
    public function testCreateArticle()
    {
        \Tests\AppBundle\Utils\Auth::logIn($this->client);

        $crawler = $this->client->request('GET', '/articles/add');
        $form = $crawler->filter('form')->form();
        $form->setValues([
            "app_article" => [
                "title" => "Article1",
                "category" => 1,
                "description" => "description"
            ]
        ]);
        $this->client->submit($form);

        $article = \Tests\AppBundle\Utils\Database::getLast($this->client, Article::class);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Article1',$article->getTitle());
        $this->assertEquals(1,$article->getCategory()->getId());
        $this->assertEquals('description',$article->getDescription());

    }
}