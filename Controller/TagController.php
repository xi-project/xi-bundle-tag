<?php

namespace Xi\Bundle\TagBundle\Controller;

use Xi\Bundle\AjaxBundle\Controller\JsonResponseController;

class TagController extends JsonResponseController
{
    public function tagAction()
    {
        return $this->render('XiTagBundle:Tag:tag.html.twig');
    }
    
    public function searchAction()
    {

        return $this->createJsonSuccessResponse(
            $this->getTagService()->searchTagForJson($this->getRequest()->get('term'))
        );
    }
    
    public function addAction()
    {
        $tag = $this->getTagService()->saveTag($this->getRequest()->get('term'));
        
        return $this->createJsonSuccessResponse(
            array('id' => $tag->getId(), 'value' => $tag->getName())
        );
    }
    
    /**
     * @return TagService
     */
    protected function getTagService()
    {
        return $this->container->get('xi_tag.service.tag');
    }    
}