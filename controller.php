<?php
namespace Concrete\Package\FormAdvancedCheckbox;

use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Package\FormAdvancedCheckbox\Express\Form\Control\View\AttributeKeyFormView;
use Concrete\Core\Attribute\Category\CategoryService;
use Concrete\Core\Attribute\TypeFactory;
use Concrete\Core\Express\Form\Context\FormContext;
use Concrete\Core\Form\Context\Registry\ControlRegistry;
use Concrete\Core\Support\Facade\Facade;
use Concrete\Core\Package\Package;

class Controller extends Package implements ProviderAggregateInterface
{
    protected $pkgHandle = 'form_advanced_checkbox';
    protected $appVersionRequired = '8.2.1';
    protected $pkgVersion = '1.0.0';
    protected $pkgAutoloaderRegistries = [
        'src' => 'Concrete\Package\FormAdvancedCheckbox',
    ];

    public function getPackageName()
    {
        return t('Advanced Checkbox Attribute');
    }

    public function getPackageDescription()
    {
        return t('Checkbox attribute with CkEditor label');
    }

    public function on_start()
    {
        $this->app->bind(ControlRegistry::class, Form\Context\Registry\ControlRegistry::class);
        $this->app->extend(ControlRegistry::class, function (ControlRegistry $registry, $app) {
            $registry->register(new FormContext(), 'express_control_attribute_key', AttributeKeyFormView::class);

            return $registry;
        });
    }

    public function install()
    {
        $pkg = parent::install();
        $app = Facade::getFacadeApplication();
        $atFactory = $app->make(TypeFactory::class);

        $at = $atFactory->getByHandle('advanced_checkbox');
        if (!is_object($at)) {
            $at = $atFactory->add('advanced_checkbox', 'Advanced checkbox', $pkg);
        }

        $siteAkc = $app->make(CategoryService::class)->getByHandle('site');
        if (is_object($siteAkc)) {
            $siteAkc->getController()->associateAttributeKeyType($at);
        }
    }

    public function uninstall()
    {
        parent::uninstall();
        $db = \Database::connection();
        $tablesToDrop = ['atAdvancedCheckboxSettings', 'atAdvancedCheckbox'];
        foreach ($tablesToDrop as $table) {
            if ($db->tableExists($table)) {
                $db->executeQuery(sprintf('DROP TABLE %s', $table));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityManagerProvider()
    {
        return new StandardPackageProvider($this->app, $this, [
            'src/Concrete/Entity' => __NAMESPACE__ . '\Entity',
        ]);
    }
}
