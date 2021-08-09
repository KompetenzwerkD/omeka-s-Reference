<?php declare(strict_types=1);
namespace Reference;

use Omeka\Module\AbstractModule;
use Omeka\Permissions\Assertion\OwnsEntityAssertion;
use Laminas\Mvc\MvcEvent;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Reference\Form\NewReferenceForm;
use Omeka\Api\Exception\NotFoundException;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\Mvc\Controller\AbstractController;

class Module extends AbstractModule 
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        $acl->allow(
            null,
            \Reference\Api\Adapter\ReferenceAdapter::class,
            ['search', 'read']
        );
        $acl->allow(
            null,
            \Reference\Entity\Reference::class,
            ['read']
        );
        $acl->allow(
            'editor',
            \Reference\Api\Adapter\ReferenceAdapter::class,
            ['create', 'update', 'delete']
        );
        $acl->allow(
            'editor',
            \Reference\Entity\Reference::class,
            ['create']
        );
        $acl->allow(
            'editor',
            \Reference\Entity\Reference::class,
            ['update', 'delete'],
            new OwnsEntityAssertion
        );
    }

    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $this->api = $serviceLocator->get('Omeka\ApiManager');        
        $dataFilepath = OMEKA_PATH . '/modules/Reference/data/'; 
        foreach($this->listFilesInDir($dataFilepath . 'resource_templates') as $filepath) {  
            $this->createResourceTemplate($filepath);
        }

        $conn = $serviceLocator->get('Omeka\Connection');
        $conn->exec('CREATE TABLE reference (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, bibl_id INT DEFAULT NULL, ref VARCHAR(190) DEFAULT NULL, INDEX IDX_AEA34913126F525E (item_id), INDEX IDX_AEA34913A5BCAC94 (bibl_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;');
        $conn->exec('ALTER TABLE reference ADD CONSTRAINT FK_AEA34913126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE SET NULL;');
        $conn->exec('ALTER TABLE reference ADD CONSTRAINT FK_AEA34913A5BCAC94 FOREIGN KEY (bibl_id) REFERENCES item (id) ON DELETE SET NULL;');
    }

    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $conn = $serviceLocator->get('Omeka\Connection');    
        $conn->exec('SET FOREIGN_KEY_CHECKS=0;');
        $conn->exec('DROP TABLE reference');
        $conn->exec('DROP TABLE reference_setup');
        $conn->exec('SET FOREIGN_KEY_CHECKS=1;');        
    }

    private function getResourceClassId($propertyName) 
    {
        $class = $this->api->read('resource_classes', ["label" => $propertyName])->getContent();
        return $class->id();
    }

    private function getPropertyId($propertyName) 
    {
        $prop = $this->api->read('properties', ["label" => $propertyName])->getContent();
        return $prop->id();
    }    

    public function createResourceTemplate(string $filepath) {
        $data = json_decode(file_get_contents($filepath), true);

        $label = $data['o:label'];
        try 
        {
            $template = $this->api->read('resource_templates', ['label' => $label]);
        } catch( NotFoundException $e) 
        {
            #set resource class
            $data['o:resource_class']['o:id'] = $this->getResourceClassId(
                $data['o:resource_class']['label']
            );

            #set title property
            if ($data['o:title_property']) {
                $data['o:title_property']['o:id'] = $this->getPropertyId(
                    $data['o:title_property']['label']
                );
            }
            #set template properties
            foreach($data['o:resource_template_property'] as $key => $prop) {
                $data['o:resource_template_property'][$key]['o:property']['o:id'] = $this->getPropertyId(
                    $prop['label']
                );
            }

            $this->api->create('resource_templates', $data);
        }

    }

    protected function listFilesInDir(string $dirpath): array 
    {
        if (empty($dirpath) || !file_exists($dirpath) || !is_dir($dirpath) || !is_readable($dirpath) )  
        {   
            return [];
        }

        $list = array_map(function ($file) use ($dirpath) 
        {
            return $dirpath . DIRECTORY_SEPARATOR . $file;
        }, scandir($dirpath));
        $list = array_filter($list, function ($file) 
        {
            return is_file($file) && is_readable($file) && filesize($file);
        });

        return array_values($list);
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $form = $this->getServiceLocator()->get('FormElementManager')->get('Reference\Form\ModuleConfigForm');
        $form->init();
        $templates = $settings->get('resourceTemplates');

        $form->setData([
            'resource-templates' => $templates,
        ]);
        return $renderer->formCollection($form, false);
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $form = $this->getServiceLocator()->get('FormElementManager')->get('Reference\Form\ModuleConfigForm');
        $form->init();
        $form->setData($controller->params()->fromPost());
        if ($form->isValid()) {
            $formData = $form->getData();
            $settings->set('resourceTemplates', $formData['resource-templates']);
            return true;
        }
        $controller->messenger()->addErrors($form->getMessages());
        return false;
    }    

    public function attachListeners(SharedEventManagerInterface $sharedEventManager): void 
    {
        $sharedEventManager->attach(
            'Omeka\Controller\Admin\Item',
            'view.show.after',
            [$this, 'addReferenceField']
        );
    }

    public function addReferenceField($event) 
    {
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $templates = $settings->get('resourceTemplates');
        $templates = array_map('trim', explode('<br />', nl2br($templates)));

        $view = $event->getTarget();

        $item = $event->getTarget()->vars()->resource;
        if ($item->resourceTemplate()) {
            if (in_array($item->resourceTemplate()->label(), $templates))
            {
                echo $view->addReferenceForm($view->vars()->resource);
            }
        }

    }
}