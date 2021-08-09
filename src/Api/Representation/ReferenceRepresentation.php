<?php
namespace Reference\Api\Representation;


use Omeka\Api\Representation\AbstractEntityRepresentation;

class ReferenceRepresentation extends AbstractEntityRepresentation
{
    public function getControllerName()
    {
        return 'reference';
    }

    public function getJsonLdType()
    {
        return 'o:Reference';
    }

    public function getJsonLd()
    {
        $item = null;
        $bibl = null;

        if ($this->item()) {
            $item = $this->item()->getReference();
        }
        if ($this->bibl()) {
            $bibl = $this->bibl()->getReference();
        }

        return [
            'o:item' => $item,
            'o:bibl' => $bibl,
            'o:ref' => $this->ref()
        ];
    }


    public function item() 
    {
        return $this->getAdapter('items')->getRepresentation($this->resource->getItem());
    }

    public function bibl()
    {
        return $this->getAdapter('items')->getRepresentation($this->resource->getBibl());
    }

    public function ref()
    {
        return $this->resource->getRef();
    }
}