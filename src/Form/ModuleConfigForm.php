<?php
namespace Reference\Form;

use Laminas\Form\Form;

class ModuleConfigForm extends Form
{
    public function init()
    {
        $this->add([
            'type' => 'textarea',
            'name' => 'resource-templates' ,
            'options' => [
                'label' => 'Resource templates',
                'info' => 'Enter the names of resource templates to activate references.'
            ],
            'attributes' => [
                'required' => false,
                'id' => 'resource-templates'
            ]
            ]);
    }
}