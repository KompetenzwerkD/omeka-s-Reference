<?php declare(strict_types=1);
namespace Reference\Service\ViewHelper;

use Reference\View\Helper\AddReferenceForm;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AddReferenceFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $formElementManager = $services->get('FormElementManager');
        return new AddReferenceForm($formElementManager);
    }
}