<?php

namespace Xi\Bundle\TagBundle\Tests\Form\Type;

use PHPUnit_Framework_Testcase,
    Xi\Bundle\TagBundle\Form\Type\TagType,
    FPN\TagBundle\Entity\TagManager,
    Doctrine\Common\Collections\ArrayCollection,
    Xi\Bundle\TagBundle\Form\DataTransformer\TagTransformer,
    Symfony\Component\Form\FormBuilder;

/**
 * @group formType
 * @group tag
 */ 
class TagTypeTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @var FormBuilder
     */
    protected $builder;
    
    public function setUp()
    {
        parent::setUp();     
        $this->tagManager = $this->getMockBuilder('FPN\TagBundle\Entity\TagManager')->disableOriginalConstructor()->getMock();
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->builder = new FormBuilder('name', $this->factory, $this->dispatcher);       
    }
    
    /**
     * @test
     */      
    public function TagTransformerExist()
    {
        $tagType = new TagType($this->tagManager);  
        $tagType->buildForm($this->builder, array());
        $transformers = $this->builder->getClientTransformers();
        
        $this->assertTrue(in_array(new TagTransformer($this->tagManager), $transformers));
    }

}