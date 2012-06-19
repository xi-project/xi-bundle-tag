<?php
namespace Xi\Bundle\TagBundle\Tests\Form\DataTransformer;

use PHPUnit_Framework_Testcase,
    Xi\Bundle\TagBundle\Form\DataTransformer\TagTransformer,
    FPN\TagBundle\Entity\TagManager,
    FPN\TagBundle\Entity\Tag,
    Doctrine\Common\Collections\ArrayCollection;
/**
 * @group transformer
 * @group tag
 */
class TagTransformerTest extends PHPUnit_Framework_Testcase
{
     /**
     * @var TagManager
     */
    protected $tagManager;

    public function setUp()
    {
        parent::setUp();
    }
    
    /**
     * @test
     */  
    public function reverseTransformTest()
    {
       $this->tagManager = $this->getMockBuilder('FPN\TagBundle\Entity\TagManager')->disableOriginalConstructor()->getMock();
       $this->tagManager->expects($this->once())->method('loadOrCreateTags')->with(array('bar','foo', 'xoo'))->will($this->returnValue($this->createTags(array('bar','foo', 'xoo'))));

       $tagTransformer = new TagTransformer($this->tagManager);
       $collection = new ArrayCollection(array('bar','foo', 'xoo'));  
     
       $newCollection = $tagTransformer->reverseTransform($collection);
            
       $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $newCollection);
       $this->assertEquals($collection->toArray(), $this->getTagNamesCollection($newCollection)->toArray());     
    }
  
    /**
     * @param ArrayCollection $collection
     * @return ArrayCollection 
     */
    private function getTagNamesCollection(ArrayCollection $collection){
        return $collection->map(function($tag) { 
           return $tag->getName();
        });
    }

    /**
     * @param array $names
     * @return \FPN\TagBundle\Entity\Tag 
     */
    private function createTags($names)
    {
        $tags = array();
        foreach($names as $name){
            $tag =  new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }
        return $tags;
    }
    
}