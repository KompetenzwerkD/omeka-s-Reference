<?php declare(strict_types=1);
namespace Reference\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class ReferencedItemsWidget extends AbstractHelper
{
    public function __invoke()
    {
        $view = $this->getView();
        $refs = $view->api()->search('references', [ 'bibl_id' => $view->resource->id() ])->getContent();

        usort($refs, function ($a, $b) {
            $aLabel = strval($a->item()->displayTitle());
            $bLabel = strval($b->item()->displayTitle());
            return $aLabel <=> $bLabel;
        });        

        $view->vars()->offsetSet('refs', $refs);
        return $view->partial('common/referenced-items-widget');
    }
}