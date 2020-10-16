<?php
namespace Concrete\Package\FormAdvancedCheckbox\Express\Form\Control\View;

use Concrete\Core\Express\Form\Context\ContextInterface;
use Concrete\Core\Express\Form\Control\View\AttributeKeyFormView as CoreAttributeKeyFormView;

class AttributeKeyFormView extends CoreAttributeKeyFormView
{
    public function __construct(ContextInterface $context, $control)
    {
        parent::__construct($context, $control);

        if ($this->key->getAttributeTypeHandle() == 'advanced_checkbox') {
            $this->view->setSupportsLabel($this->key->getAttributeKeySettings()->supportsTitle());
            // Hide asterix / Required term if no label is displayed
            if (!$this->view->supportsLabel()) {
                $this->view->setIsRequired(false);
            }
        }
    }
}
