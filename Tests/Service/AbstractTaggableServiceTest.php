<?php

namespace Xi\Bundle\TagBundle\Tests\Service;

use PHPUnit_Framework_Testcase,
    Xi\Bundle\TagBundle\Service\AbstractTaggableService,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Component\DependencyInjection\ContainerInterface;


use DoctrineExtensions\Taggable\TagManager;
use DoctrineExtensions\Taggable\TagListener;
use DoctrineExtensions\Taggable\Entity\Tag;
use Tests\DoctrineExtensions\Taggable\Fixtures\Article;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

/**
 * @group service
 * @group tag
 * @group AbstractTaggableService
 */
class AbsractTaggableServiceTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var AbstractTaggableService
     */
    protected $abstractTaggableservice;
    
    
    public function setUp()
    {
        parent::setUp();   


        $this->container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->disableOriginalConstructor()->getMock();
        
        $this->abstractTaggableservice = $this->getMockBuilder('Xi\Bundle\TagBundle\Service\AbstractTaggableService')
            ->setConstructorArgs(array($this->container))
            ->setMethods(array('getTaggableType','getTaggedResourcesByIds'))
            ->getMockForAbstractClass();
    }

    /**
     * @test
     */     
    public function getTagServiceIdentifier()
    { 
        $this->assertEquals('xi_tag.service.tag', $this->abstractTaggableservice->getTagServiceIdentifier());
    }
    
    /**
     * @test
     */     
    public function setAndGetTagServiceIdentifier()
    { 
        $this->abstractTaggableservice->setTagServiceIdentifier('foo'); 
        $this->assertEquals('foo', $this->abstractTaggableservice->getTagServiceIdentifier());
    }    
    
    /**
     * @test
     */     
    public function getTagService()
    { 
        $this->container->expects($this->once())->method('get')->with('xi_tag.service.tag');
        $this->abstractTaggableservice->getTagService();    
    }


}