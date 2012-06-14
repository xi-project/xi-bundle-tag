<?php

namespace Xi\Bundle\TagBundle\Service;

use Doctrine\ORM\EntityManager,
    Xi\Doctrine\ORM\Repository,
    Xi\Bundle\TagBundle\Entity\Tag,
    Xi\Bundle\TagBundle\Repository\TagRepository,
    FPN\TagBundle\Entity\TagManager,
    Doctrine\Common\Collections\ArrayCollection;

class TagService
{
    /**
     * @var ArrayCollection 
     */
    protected $serviceReferences;
    
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
    /**
     * @param EntityManager $em
     * @param TagRepository $repository
     * @param TagManager $tagManager
     */    
    public function __construct(EntityManager $em, TagRepository $repository,  TagManager $tagManager)
    {
        $this->em           = $em;
        $this->repository   = $repository;      
        $this->tagManager   = $tagManager;
        
        $this->serviceReferences = new ArrayCollection();       
    }
    
    /**
     * Creates reference entities from an array of tag ids.
     * 
     * @param  array $tags ids
     * @return array containing Xi\Bundle\TagBundle\Entity\Tag object references
     */
    public function getTagReferences(array $tags)
    {
        $em = $this->em;        
        return array_map(
            function($tag) use($em) { return $em->getReference('Xi\Bundle\TagBundle\Entity\Tag', $tag); },
            $tags
        );
    }
 
    /**
     * @param int $int
     */
    public function getTagById($id)
    {
        return $this->repository->find($id);
    }
    
    
    /**
     * @param string $value 
     */
    public function getTagByValue($value)
    {
        return $this->repository->findOneByName($value);
    }
    
    /**
     * @param array $values
     * @return array(Tag) 
     */
    public function getTagsByValues(array $values)
    {
        return $this->repository->findByName($values);
    }
    
    /**
     * @param  string $value 
     * @return Tag
     */
    public function searchTag($value)
    {
        return $this->repository->searchByTagName($value);
    }
    
    /**
     * @param  string $value
     * @return array
     */
    public function searchTagForJson($value)
    {     
        return array_map(
            function($tag) { return array('id' => $tag->getId(), 'value' => $tag->getName()); },
            $this->searchTag($value)
        );
    }
    
    /**
     * Does not allow persisting of duplicate Tags.
     * Return existing Tag if found with same value.
     * 
     * @param  string $value
     * @return Tag
     */
    public function saveTag($value)
    {               
        return $this->tagManager->loadOrCreateTag($value);
    }
    
    /**
     * Registers service references so we can call right service with correct taggableType  
     * 
     * @param TaggableService $service
     * @return TagService
     */
    public function addServiceReference(TaggableService $service)
    {
        $resourceName = $service->getTaggableType();
        if(!$this->serviceReferences->containsKey($resourceName))
        {
            $this->serviceReferences->set($resourceName, $service);
        }
        return $this;
    }
   /**
    * get registered service
    * 
    * @param string $resourceName
    * @return TaggableService $service
    */
    public function getService($resourceName)
    {
        if(!$this->serviceReferences->containsKey($resourceName))
        {
            throw new \InvalidArgumentException('Invalid reference name. Reference name is not in the list of registered references.');
        }
        return $this->serviceReferences->get($resourceName);
    }

    /**
     * @param array $resourceNames
     * @param array $tagNames
     * @return array resources
     */
    public function getResources(array $resourceNames, array $tagNames)
    {     
        $entities = array();
        foreach ($resourceNames as $resourceName => $resourceOptions)
        {
            if(is_string($resourceOptions)) {
                $resourceName    = $resourceOptions;
                $resourceOptions = array();
            }
            $resourceIds = $this->repository->getResourceIdsForTags($resourceName, $tagNames, true);
            $service = $this->getService($resourceName);

            if(isset($resourceOptions['callback'])){           
                if(method_exists($service, $resourceOptions['callback'])) {           
                    $entities[$resourceName] = $service->$resourceOptions['callback']($resourceIds, $resourceOptions, $tagNames);
                } else {
                    throw new \BadMethodCallException($resourceOptions['callback'].' is not defined in your '.$resourceName.' class');
                }
             
            } else if (!empty($resourceIds)){
                $entities[$resourceName] = $service->getTaggedResourcesByIds($resourceIds, $resourceOptions, $tagNames);
            }
        }
        return $entities;
    }
  
    /**
     * @return TagManager
     */
    public function getTagManager()
    {
        return $this->tagManager;
    }
    
}