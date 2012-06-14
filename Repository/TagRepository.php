<?php

namespace Xi\Bundle\TagBundle\Repository;

use Doctrine\ORM\EntityRepository,
    DoctrineExtensions\Taggable\Entity\TagRepository as ParentRepository,
    Doctrine\ORM\AbstractQuery;

class TagRepository extends ParentRepository
{
    /**
     * @param string $tagName
     * @return array 
     */
    public function searchByTagName($tagName)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
           ->from('Xi\Bundle\TagBundle\Entity\Tag', 't')
           ->where('t.name LIKE ?1')
           ->setParameter(1, $tagName . '%');
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * @param string $name
     * @return Tag 
     */
    public function findOneByName($name)
    {
        return parent::findOneByName($name);
    }
    
    /**
     * @param string $name
     * @return array
     */
    public function findByName($name)
    {
        return parent::findByName($name);
    }

    /**
     * @param string $resourceName
     * @param array $tagNames
     * @param bool  $usewildcard
     * @return array
     */
    public function getResourceIdsForTags($resourceName, $tagNames, $useWildcard = false)
    {
        $wildcard = '';
        if($useWildcard){
            $wildcard = '%';
        }
        
        $qb = $this->getTagsQueryBuilder($resourceName);
        
        $orParts = array();
        foreach($tagNames as $tag)
        {
            $orParts[] =$qb->expr()->like('tag.'.$this->tagQueryField, $qb->expr()->literal($tag.$wildcard));
        }  
        $whereExpr = call_user_func_array(array($qb->expr(), 'orX'), $orParts);
        
        $results = $qb->select('tagging.resourceId')
            ->andWhere($whereExpr)
            ->getQuery()  
            ->execute(array(), AbstractQuery::HYDRATE_SCALAR);

        $ids = array();
        foreach ($results as $result) {
            $ids[] = $result['resourceId'];
        }

        return $ids;
    }  
    
    
    
}