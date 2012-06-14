<?php
namespace Xi\Bundle\TagBundle\Tests\Form\DataTransformer;

use PHPUnit_Framework_Testcase,
    Xi\Bundle\TagBundle\Form\DataTransformer\TagTransformer,
    FPN\TagBundle\Entity\TagManager,
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
        
        $this->tagManager = $this->getContainer()->get('fpn_tag.tag_manager');
    }
    
    /**
     * @test
     */  
    public function reverseTransformTest()
    {
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

}