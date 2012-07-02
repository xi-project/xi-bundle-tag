<?php

namespace Xi\Bundle\TagBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Xi\Bundle\TagBundle\Form\DataTransformer\TagTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use FPN\TagBundle\Entity\TagManager;

class TagType extends CollectionType
{
    /**
     * @var TagManager 
     */
    private $tagManager;

    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }
       
    public function getParent()
    {
        return 'collection';
    }
    
    public function getName()
    {
        return 'tag';
    }
    
    public function getDefaultOptions()
    {
        return array(
            'allow_add'     => true,
            'allow_delete'  => false,
            'prototype'     => true,
            'type'          => 'text',
            'options'       => array(),
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagTransformer($this->tagManager);
        $builder->appendClientTransformer($transformer);
    }
}