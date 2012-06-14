<?php

namespace Xi\Bundle\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    \FPN\TagBundle\Entity\Tagging as BaseTagging;

/**
 * Xi\Bundle\TagBundle\Entity\Tagging
 *
 * @ORM\Table(name="xi_tagging", uniqueConstraints={@ORM\UniqueConstraint(name="tagging_idx", columns={"tag_id", "resource_type", "resource_id"})})
 * @ORM\Entity()
 */ 
class Tagging extends BaseTagging
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
     * @ORM\ManyToOne(targetEntity="Xi\Bundle\TagBundle\Entity\Tag", inversedBy="tagging")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    protected $tag;
    
}

