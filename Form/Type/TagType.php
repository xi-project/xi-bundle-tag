<?php

namespace Xi\Bundle\TagBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Xi\Bundle\TagBundle\Form\DataTransformer\TagTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use FPN\TagBundle\Entity\TagManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagType extends CollectionType
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

    /**
     * @return string
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tag';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_add'     => true,
            'allow_delete'  => false,
            'prototype'     => true,
            'type'          => 'text',
            'options'       => array('data' => true), // @todo: find out why this is required
        ));
    }

    /**
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagTransformer($this->tagManager);
        $builder->addViewTransformer($transformer);
    }
}