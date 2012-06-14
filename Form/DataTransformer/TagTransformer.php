<?php
namespace Xi\Bundle\TagBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Xi\Bundle\TagBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use FPN\TagBundle\Entity\TagManager;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @var TagManager 
     */
    private $tagManager;

    /**
     * @param TagManager $tagManager
     */
    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }


    public function transform($collection)
    {
        return $collection;
    }

    /**
     * transforms collection with tag names to collection with tag entities
     * 
     * @param ArrayCollection $collection
     * @return ArrayCollection 
     */
    public function reverseTransform($collection)
    {
        if($collection instanceof ArrayCollection ) {  
            $tags = $this->tagManager->loadOrCreateTags(array_filter($collection->toArray()));
            return new ArrayCollection($tags);
        }
        return $collection;
    }
}