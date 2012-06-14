<?php

namespace Xi\Bundle\TagBundle\Tests\Service;

use PHPUnit_Framework_Testcase,
    Xi\Bundle\TagBundle\Service\TagService,
    Doctrine\Common\Collections\ArrayCollection;

use DoctrineExtensions\Taggable\TagManager;
use DoctrineExtensions\Taggable\TagListener;
use DoctrineExtensions\Taggable\Entity\Tag;
use Tests\DoctrineExtensions\Taggable\Fixtures\Article;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

/**
 * @group service
 * @group tag
 * @group TagService
 */
class TagServiceTest extends PHPUnit_Framework_Testcase
{
  

    /**
     * @var TagService 
     */
    protected $tagService;
    
    /**
     * @var EntityManager
     */
    protected $em;
    
    /**
     * @var TagRepository
     */
    protected $repository;
    
    /**
     * @var TagManager 
     */
    protected $tagManager;
    
    
    protected $abstractTaggableservice;
    
    
    public function setUp()
    {
        parent::setUp();   
      
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->repository = $this->getMockBuilder('Xi\Bundle\TagBundle\Repository\TagRepository')->disableOriginalConstructor()->getMock();
        $this->tagManager = $this->getMockBuilder('FPN\TagBundle\Entity\TagManager')->disableOriginalConstructor()->getMock();    
        $this->tagService = new TagService($this->em, $this->repository, $this->tagManager);
  
        $this->abstractTaggableservice = $this->getMockBuilder('Xi\Bundle\TagBundle\Service\AbstractTaggableService')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }
    
    /**
     * @test
     */     
    public function getTagByValue()
    { 
        $this->repository->expects($this->once())->method('findOneByName')->with('foo');
        $this->tagService->getTagByValue('foo');    
    }
  
    /**
     * @test
     */     
    public function getTagsByValues()
    { 
        $this->repository->expects($this->once())->method('findByName')->with(array('foo', 'bar'));
        $tag = $this->tagService->getTagsByValues(array('foo', 'bar')); 
    }
    
    /**
     * @test
     */  
    public function searchTag()
    {
        $this->repository->expects($this->once())->method('searchByTagName')->with('foo');
        $this->tagService->searchTag('foo');    
    }

    /**
     * @test
     */  
    public function searchTagForJson()
    {
        $tags = $this->createTags(3);
        $this->repository->expects($this->once())->method('searchByTagName')->will($this->returnValue(array($tags[1])));
        $searchResult = $this->tagService->searchTagForJson('tag1');
        $expected = array(0 => array('id' => null, 'value' => 'tag1'));
        $this->assertSame($expected, $searchResult);  
    }    
    
    /**
     * @test
     */   
    public function saveTag()
    {
        $this->tagManager->expects($this->once())->method('loadOrCreateTag');
        $this->tagService->saveTag('test');
    }
    
    /**
     * @test
     */         
    public function addServiceReferenceAndGetServiceByReference()
    {
        $this->abstractTaggableservice->expects($this->atLeastOnce())->method('getTaggableType')->will($this->returnValue('puuppa'));
        $this->tagService->addServiceReference($this->abstractTaggableservice);
        $this->assertSame($this->abstractTaggableservice, $this->tagService->getService('puuppa'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */     
    public function tagServiceShouldThrowExceptionWhenResourseIsNotRegistered()
    {
        $resources = $this->tagService->getResources(array('resourcename' => 'resourcename'), array('foo0', 'foo1'));
    }
    
    /**
     * @test
     */     
    public function tagServiceShouldreturnEmptyPuuppaArrayIfEverythingWorks()
    {
        $resourseIds = array(1,2,3);
        $resourceOptions = array();
        $tagNames    = array('foo', 'bar');
        
        $this->abstractTaggableservice->expects($this->atLeastOnce())->method('getTaggableType')->will($this->returnValue('puuppa'));
        $this->abstractTaggableservice->expects($this->once())->method('getTaggedResourcesByIds')->with($resourseIds, $resourceOptions, $tagNames);
         
        $this->tagService->addServiceReference($this->abstractTaggableservice);           
        $this->repository->expects($this->once())->method('getResourceIdsForTags')->will($this->returnValue($resourseIds));
        
        $resourses = $this->tagService->getResources(array('puuppa' => 'puuppa'), $tagNames);
        
        $this->assertTrue(array_key_exists('puuppa', $resourses));
    }
    
    /**
     * @test
     * @expectedException BadMethodCallException
     */     
    public function tagServiceCallNotDefinedCustomCallback()
    {
        $resourseIds = array(1,2,3);
        $resourceOptions = array();
        $tagNames    = array('foo', 'bar');
        
        $this->abstractTaggableservice->expects($this->atLeastOnce())->method('getTaggableType')->will($this->returnValue('puuppa'));
      
        $this->tagService->addServiceReference($this->abstractTaggableservice);           
        $this->repository->expects($this->any())->method('getResourceIdsForTags')->will($this->returnValue($resourseIds));
        
        $resourses = $this->tagService->getResources(array('puuppa' => array('callback' => 'customPuuppa')), $tagNames);
        
        $this->assertTrue(array_key_exists('puuppa', $resourses));
    } 
    
    /**
     *
     * @param int $numberOfTags
     * @return array(Tag) 
     */
    private function createTags($numberOfTags)
    {
        $tags = array();
        for($i= 0; $i < $numberOfTags; $i++){
           $tag = new Tag();
           $tag->setName('tag'.$i);
  
           $tags[] = $tag;
        }  
        return $tags;
    }

}