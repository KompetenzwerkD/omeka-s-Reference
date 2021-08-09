<?php
namespace Reference\Form;

use Omeka\Form\Element\ResourceSelect;
use \Laminas\Form\Form;
use \Laminas\Form\Element\Submit;

class NewReferenceForm extends Form
{
    public function init()
    {
        $this->add([
            'name' => 'o:bibl',
            'type' => ResourceSelect::class,
            'options' => [
                'label' => 'Bibliographic resource', 
                'info' => 'Select bibliographic resource', 
                'empty_option' => '', 
                'resource_value_options' => [
                    'resource' => 'items',
                    'query' => [ 'resource_class_label' => 'Bibliographic Resource', 'sort_by' => 'title'],
                    'option_text_callback' => function ($item) {
                        $code = $item->value('dcterms:alternative');
                        $title = $item->value('dcterms:title');
                        if ($code) {
                            $option = sprintf("[%s] %s", strval($code), strval($title));
                        } else {
                            $option = strval($title);
                        }
                        return $option;
                    },
                ],
            ],
            'attributes' => [
                'required' => true,
                'class' => 'chosen-select',
                'data-placeholder' => 'Select bibliographic resource'
            ],
        ]);
        $this->add([
            'name' => 'o:ref',
            'type' => 'text',
            'options' => [
                'label' => 'Ref.',
                'info' => 'Additional reference information',
            ]
        ]);
        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'id' => 'submit',
                'value' => 'Add', // @translate
            ],
        ]);
    }
}