<?php declare(strict_types=1);
namespace Reference\View\Helper;

use Reference\Entity\Reference;
use Reference\Form\NewReferenceForm;
use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class AddReferenceForm extends AbstractHelper
{
    protected $formElementManager;

    public function __construct($formElementManager)
    {
        $this->formElementManager = $formElementManager;
    }

    public function __invoke(AbstractResourceEntityRepresentation $resource)
    {
        $view = $this->getView();
        $form = $this->formElementManager->get(NewReferenceForm::class);

        $refs = $view->api()->search('references', [ 'item_id' => $resource->id() ])->getContent();

        $view->vars()->offsetSet('resource', $resource);
        $view->vars()->offsetSet('refForm', $form);
        $view->vars()->offsetSet('refs', $refs);
        return $view->partial('common/reference-form');
    }

}