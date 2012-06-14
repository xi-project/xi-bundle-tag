<?php

namespace Xi\Bundle\TagBundle\Service;

use Xi\Bundle\TagBundle\Service\TagService;

interface TaggableService
{    
    /**
     * get taggable resource name
     * 
     * @return string
     */    
    public function getTaggableType();
   
    /**
     * @param array $ids
     * @param array $options
     * @param array $tagNames
     * @return resources
     */
    public function getTaggedResourcesByIds(array $ids, array $options, array $tagNames);
    
    /**
     * @return TagService
     */
    public function getTagService();
}