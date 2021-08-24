<?php
namespace Reference\Api\Adapter;

use Omeka\Api\Adapter\AbstractEntityAdapter;
use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\Stdlib\ErrorStore;

class ReferenceAdapter extends AbstractEntityAdapter
{
    public function getResourceName()
    {
        return "references";
    }

    public function getRepresentationClass()
    {
        return \Reference\Api\Representation\ReferenceRepresentation::class;
    }

    public function getEntityClass()
    {
        return \Reference\Entity\Reference::class;
    }

    protected function getValue($request, $resource, $elem) 
    {
        $value = $request->getValue($elem);
        if ($value && isset($value['o:id'])) 
        {
            return  $this->getAdapter($resource)->findEntity($value['o:id']);
        }
        else 
        {
            return null;
        }
    }

    public function hydrate(
        Request $request,
        EntityInterface $entity,
        ErrorStore $errorStore
    ) {
        if ($this->shouldHydrate($request, 'o:item')) {
            $item = $this->getValue($request, 'items', 'o:item');
            $entity->setItem($item);
        }
        if ($this->shouldHydrate($request, 'o:bibl')) {
            $bibl = $this->getValue($request, 'items', 'o:bibl');
            $entity->setBibl($bibl);
        }
        if ($this->shouldHydrate($request, 'o:ref')) {
            $entity->setRef($request->getValue('o:ref'));
        }
    }

    public function buildQuery(QueryBuilder $qb, array $query): void 
    {
        $expr = $qb->expr();
        if (isset($query['item_id'])) {
            if (is_null($query['item_id'])) {
                $qb->andWhere($expr->isNull(
                    'omeka_root.' . 'item',
                ));
            } else {
                $qb->andWhere($expr->eq(
                    'omeka_root.' . 'item',
                    $this->createNamedParameter($qb, $query['item_id'])
                ));
            }
        }
        if (isset($query['bibl_id'])) {
            if (is_null($query['bibl_id'])) {
                $qb->andWhere($expr->isNull(
                    'omeka_root.' . 'bibl',
                ));
            } else {
                $qb->andWhere($expr->eq(
                    'omeka_root.' . 'bibl',
                    $this->createNamedParameter($qb, $query['bibl_id'])
                ));
            }
        }        
    }
}