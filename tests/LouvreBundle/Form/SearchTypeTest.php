<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 02/06/2017
 * Time: 10:16
 */

namespace tests\LouvreBundle\Form;


use LouvreBundle\Form\SearchType;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class SearchTypeTest
 * @package tests\LouvreBundle\Form
 */
class SearchTypeTest extends TypeTestCase
{


    public function testSubmitValidData ()
    {
        $formData = [
            'email'  => 'balohe37@gmail.com'
            ];

        $form = $this->factory->create(SearchType::class);



        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());


        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key)
        {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
