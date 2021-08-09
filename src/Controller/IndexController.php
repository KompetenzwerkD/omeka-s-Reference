<?php
namespace Reference\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Reference\Form\NewReferenceForm;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function addAction() 
    {
        $id = $this->params()->fromRoute('id');
        $item = $this->api()->read('items', $id)->getContent();

        if ($this->getRequest()->isPost())
        {
            $form = $this->getForm(NewReferenceForm::class);
            $form->setData($this->params()->fromPost());
            if ($form->isValid())
            {
                $formData = $form->getData();
                $formData['o:item'] = ['o:id' => $id];
                $formData['o:bibl']  = ['o:id' => $formData['o:bibl']];
    
                $rsp = $this->api($form)->create('references', $formData);
                if ($rsp) {
                    $this->messenger()->addSuccess("Reference added");
                }                
            }
        }
        return $this->redirect()->toURL($item->url());
    }

    public function showAction() {
        $id = $this->params()->fromRoute('id');
        $item = $this->api()->read('items', $id)->getContent();
        return $this->redirect()->toURL($item->url());

    }

    public function deleteConfirmAction()
    {
        $resource = $this->api()->read('references', $this->params('id'))->getContent();

        $view = new ViewModel;
        $view->setTerminal(true);
        //$view->setTemplate('common/delete-confirm-details');
        $view->setVariable('resource', $resource);
        $resourceLabel = 'references'; // @translate
        $view->setVariable('resourceLabel', $resourceLabel);
        return $view;
    }

    public function deleteAction()
    {
        $ref = $this->api()->read('references', $this->params('id'))->getContent();
        $item = $ref->item();

        $rsp = $this->api()->delete('references', $ref->id());
        if ($rsp) {
            $this->messenger()->addSuccess("Reference deleted");
        }
        return $this->redirect()->toURL($item->url());
    }
}