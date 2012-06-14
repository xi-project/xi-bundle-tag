<?php

namespace Xi\Bundle\TagBundle\Service;

use Xi\Bundle\TagBundle\Service\TagService;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractTaggableService implements TaggableService
{
    /**
     * @var TagService
     */
    private $tagService;

    /**
     * @var ContainerInterface
     */
    protected $container;

    
    protected $tagServiceIdentifier = 'xi_tag.service.tag';
        
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    
    /**
     * Sets tag service identifier
     * 
     * @param string $tagServiceIdentifier
     */
    public function setTagServiceIdentifier($tagServiceIdentifier)
    {
        $this->tagServiceIdentifier = $tagServiceIdentifier;
    }
    
    
    public function getTagServiceIdentifier()
    {
        return $this->tagServiceIdentifier;
    }
        
    
    /**
     * @return TagService
     */
    public function getTagService()
    {
        if (!$this->tagService) {
            $this->tagService = $this->container->get($this->getTagServiceIdentifier()); 
        }
        return $this->tagService;
    }
}
