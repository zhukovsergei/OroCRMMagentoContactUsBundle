<?php

namespace Oro\Bundle\MagentoContactUsBundle\Tests\Unit\Form\Type;

use Oro\Bundle\EmbeddedFormBundle\Form\Type\EmbeddedFormInterface;
use Oro\Bundle\MagentoContactUsBundle\Form\Type\ContactRequestType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactRequestTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContactRequestType */
    protected $formType;

    public function setUp()
    {
        $this->formType = new ContactRequestType();
    }

    public function tearDown()
    {
        unset($this->formType);
    }

    public function testHasName()
    {
        $this->assertEquals('oro_magento_contactus_contact_request', $this->formType->getName());
    }

    public function testImplementEmbeddedFormInterface()
    {
        $this->assertTrue($this->formType instanceof EmbeddedFormInterface);

        $this->assertNotEmpty($this->formType->getDefaultCss());
        $this->assertInternalType('string', $this->formType->getDefaultCss());

        $this->assertNotEmpty($this->formType->getDefaultSuccessMessage());
        $this->assertInternalType('string', $this->formType->getDefaultSuccessMessage());
    }

    public function testBuildForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()->getMock();

        $fields = [];
        $builder->expects($this->exactly(9))
            ->method('add')
            ->will(
                $this->returnCallback(
                    function ($fieldName, $fieldType) use (&$fields) {
                        $fields[$fieldName] = $fieldType;

                        return new \PHPUnit_Framework_MockObject_Stub_ReturnSelf();
                    }
                )
            );

        $this->formType->buildForm($builder, ['dataChannelField' => false]);

        $this->assertSame(
            [
                'firstName'              => TextType::class,
                'lastName'               => TextType::class,
                'organizationName'       => TextType::class,
                'preferredContactMethod' => ChoiceType::class,
                'phone'                  => TextType::class,
                'emailAddress'           => TextType::class,
                'contactReason'          => EntityType::class,
                'comment'                => TextareaType::class,
                'submit'                 => SubmitType::class,
            ],
            $fields
        );
    }
}
