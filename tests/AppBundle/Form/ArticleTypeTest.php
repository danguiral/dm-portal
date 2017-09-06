<?php

namespace Tests\AppBundle\Form\Type;

use AppBundle\Entity\Article;
use AppBundle\Form\Type\ArticleType;
use Symfony\Component\Form\Test\TypeTestCase;

class ArticleTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        /*$formData = [

                "title" => "Article1",
                "category" => 1,
                "description" => "description"

        ];

        $article = new Article();
        $article->fromArray($formData);



        $form = $this->factory->create('AppBundle\Form\Type\ArticleType');

        /*$object = \AppBundle\Repository\ArticleRepository::fromArray($formData);


        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }*/
    }
}

