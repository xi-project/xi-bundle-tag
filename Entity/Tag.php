<?php

namespace Xi\Bundle\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    FPN\TagBundle\Entity\Tag as BaseTag;


/**
 * Xi\Bundle\TagBundle\Entity\Tag
 *
 * @ORM\Table(name="xi_tag")
 * @ORM\Entity(repositoryClass="Xi\Bundle\TagBundle\Repository\TagRepository")
 */ 
class Tag extends BaseTag
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */   
    protected $id;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Xi\Bundle\TagBundle\Entity\Tagging", mappedBy="tag", fetch="EAGER")
     */ 
    protected $tagging;
    
    public function __toString()
    {
        return $this->name;
    }

}

