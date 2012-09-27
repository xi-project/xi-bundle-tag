# Tag functionality for Symfony2

Tag bundle provides tagging of different units inside a Symfony 2 project. Taging is done with the Selector bundle.


## Dependencies

xi-bundle-selector
* https://github.com/xi-project/xi-bundle-selector

## Installing

### deps -file
```
[XiSelectorBundle]
    git=http://github.com/xi-project/xi-bundle-selector.git
    target=/bundles/Xi/Bundle/SelectorBundle

[XiTagBundle]
    git=http://github.com/xi-project/xi-bundle-tag.git
    target=/bundles/Xi/Bundle/TagBundle
```

### autoload.php file
```php
<?php
'Xi\\Bundle'       => __DIR__.'/../vendor/bundles',
?>
```

### appKernel.php -file
```php
<?php
            new Xi\Bundle\SelectorBundle\XiSelectorBundle(),
            new Xi\Bundle\TagBundle\XiTagBundle(),
 ?>
```

### routing.yml -file
```yml
XiTagBundle:
    resource: "@XiTagBundle/Resources/config/routing.yml"
    prefix:   /
```

### config.yml -file

Your twig configuration should look something like this:

```
twig:
    debug:            %kernel.debug%
    strict_variables: %kernel.debug%
    form:
        resources:
            - 'XiTagBundle:Form:tag-fields.html.twig'
    globals:
        domain:   %domain%
```

### Using them

You will need to have proper service and entity classes available.

Skeleton class, Article. I have omitted all Doctrine specific annotations. 

#### Article (entity)

```php
namespace Loso\LosoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection,
    DoctrineExtensions\Taggable\Taggable,
    Xi\Bundle\TagBundle\Entity\Tag;

class Article implements Taggable
{
    protected $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Returns the unique taggable resource type
     *
     * @return string
     */
    public function getTaggableType()
    {
        return 'article';
    }

    /**
     * Returns the unique taggable resource identifier
     *
     * @return string
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * Returns the collection of tags for this Taggable entity
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    /**
     * @param Tag $tag
     * @return bool
     */
    public function hasTag(Tag $tag)
    {
        return $this->tags->contains($tag);
    }

    /**
     * @param array $tags
     * @return Article
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}

```

#### ArticleService

Note that this is a simple implementation that might lack necessary safety features and is only meant to be used as an example.

Remember to set up an entry in services.yml.

Something like:

loso_article.service.article:
	class:     Loso\LosoBundle\Service\ArticleService
    arguments: ["@doctrine.orm.entity_manager", "@loso_article.repository.article", "@service_container"]

```php

namespace Loso\LosoBundle\Service;

use Xi\Bundle\TagBundle\Service\AbstractTaggableService,
    Xi\Doctrine\ORM\Repository,
    Loso\LosoBundle\Entity\Article,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Doctrine\ORM\EntityManager;

class ArticleService extends AbstractTaggableService
{
    /**
     * @var EntityManager
     */
    protected $em;

    protected $repository;

    /**
     * @param EntityManager $em
     * @param Repository $repository
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, Repository $repository, ContainerInterface $container)
    {
        $this->em           = $em;
        $this->repository   = $repository;

        parent::__construct($container);
    }

    /**
     * @param int $id
     * @return Article
     */
    public function getArticle($id)
    {
        $article = $this->repository->find($id);

        if($article)
        {
            $this->getTagService()->getTagManager()->loadTagging($article);
        }

        return $article;
    }

    /**
     * @param Article $article
     */
    public function saveArticle(Article $article)
    {
        $this->em->persist($article);
        $this->em->flush();

        $this->getTagService()->getTagManager()->saveTagging($article);
    }

    /**
     * get taggable resource name
     *
     * @return string
     */
    public function getTaggableType()
    {
        return 'article';
    }

    /**
     * @param array $ids
     * @param array $options
     * @param array $tagNames
     * @return resources
     */
    public function getTaggedResourcesByIds(array $ids, array $options, array $tagNames)
    {
        return $this->repository->findById($ids);
    }
}
```