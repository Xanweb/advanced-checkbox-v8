<?php
namespace Concrete\Package\FormAdvancedCheckbox\Attribute\AdvancedCheckbox;

use Concrete\Core\Attribute\Controller as AttributeTypeController;
use Concrete\Core\Attribute\SimpleTextExportableAttributeInterface;
use Concrete\Package\FormAdvancedCheckbox\Entity\Attribute\Key\Settings\AdvancedCheckboxSettings;
use Concrete\Package\FormAdvancedCheckbox\Entity\Attribute\Value\Value\AdvancedCheckboxValue;
use Concrete\Core\Search\ItemList\Database\AttributedItemList;
use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Editor\LinkAbstractor;

class Controller extends AttributeTypeController implements SimpleTextExportableAttributeInterface
{
    protected $searchIndexFieldDefinition = ['type' => 'boolean', 'options' => ['default' => 0, 'notnull' => false]];

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('check-square');
    }

    public function searchForm($list)
    {
        $list->filterByAttribute($this->getAttributeKey()->getAttributeKeyHandle(), $this->request('value'));

        return $list;
    }

    public function filterByAttribute(AttributedItemList $list, $boolean, $comparison = '=')
    {
        $qb = $list->getQueryObject();
        $column = sprintf('ak_%s', $this->getAttributeKey()->getAttributeKeyHandle());
        switch ($comparison) {
            case '<>':
            case '!=':
                $boolean = $boolean ? false : true;
                break;
        }
        if ($boolean) {
            $qb->andWhere("{$column} = 1");
        } else {
            $qb->andWhere("{$column} <> 1 or {$column} is null");
        }
    }

    public function getCheckboxLabel()
    {
        if ($this->akContent) {
            return $this->akContent;
        }

        return $this->getAttributeKey()->getAttributeKeyName();
    }

    public function exportKey($akey)
    {
        $this->load();
        $type = $akey->addChild('type');
        $type->addAttribute('show-title', $this->akShowTitle);
        $type->addAttribute('checkbox-label', LinkAbstractor::export($this->akContent));

        return $akey;
    }

    public function importKey(\SimpleXMLElement $akey)
    {
        $type = $this->getAttributeKeySettings();
        if (isset($akey->type)) {
            $akShowTitle = (string) $akey->type['show-title'];
            $label = (string) $akey->type['checkbox-label'];
            if ('' != $akShowTitle) {
                $type->setShowTitle(true);
            }
            if ('' != $label) {
                $type->setContent(LinkAbstractor::import($label));
            }
        }

        return $type;
    }

    public function form()
    {
        $this->load();
        $checked = false;
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
            $checked = 1 == $value ? true : false;
        }

        $this->set('checked', $checked);
    }

    public function type_form()
    {
        $this->set('form', $this->app->make('helper/form'));
        $this->set('editor', $this->app->make('editor'));
        $this->load();
    }

    // run when we call setAttribute(), instead of saving through the UI
    public function createAttributeValue($value)
    {
        $v = new AdvancedCheckboxValue();
        $value = (false == $value || '0' == $value) ? false : true;
        $v->setValue($value);

        return $v;
    }

    /**
     * {@inheritdoc}
     *
     * @see AttributeTypeController::createDefaultAttributeValue()
     */
    public function createDefaultAttributeValue()
    {
        $this->load();

        return $this->createAttributeValue(false);
    }

    public function validateValue()
    {
        $v = $this->getAttributeValue()->getValue();

        return 1 == $v;
    }

    public function getSearchIndexValue()
    {
        return $this->attributeValue->getValue() ? 1 : 0;
    }

    public function saveKey($data)
    {
        $type = $this->getAttributeKeySettings();

        $akContent = $data['akContent'];
        $akShowTitle = false;
        if (isset($data['akShowTitle']) && $data['akShowTitle']) {
            $akShowTitle = true;
        }
        $type->setContent($akContent);
        $type->setShowTitle($akShowTitle);

        return $type;
    }

    public function getAttributeValueClass()
    {
        return AdvancedCheckboxValue::class;
    }

    public function getAttributeKeySettingsClass()
    {
        return AdvancedCheckboxSettings::class;
    }

    public function createAttributeValueFromRequest()
    {
        $data = $this->post();

        return $this->createAttributeValue(isset($data['value']) ? $data['value'] : false);
    }

    // if this gets run we assume we need it to be validated/checked
    public function validateForm($data)
    {
        return isset($data['value']) && 1 == $data['value'];
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Attribute\SimpleTextExportableAttributeInterface::getAttributeValueTextRepresentation()
     */
    public function getAttributeValueTextRepresentation()
    {
        $value = $this->getAttributeValueObject();
        if (null === $value || null === $value->getValue()) {
            $result = '';
        } else {
            $result = $value->getValue() ? '1' : '0';
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Attribute\SimpleTextExportableAttributeInterface::updateAttributeValueFromTextRepresentation()
     */
    public function updateAttributeValueFromTextRepresentation($textRepresentation, ErrorList $warnings)
    {
        $value = $this->getAttributeValueObject();
        $textRepresentation = trim($textRepresentation);
        if ('' === $textRepresentation) {
            if (null !== $value) {
                $value->setValue(null);
            }
        } else {
            // false values: '0', 'no', 'true' (case insensitive)
            // true values: '1', 'yes', 'false' (case insensitive)
            $bool = filter_var($textRepresentation, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (null === $bool) {
                $warnings->add(t('"%1$s" is not a valid boolean value for the attribute with handle %2$s', $textRepresentation, $this->attributeKey->getAttributeKeyHandle()));
            } else {
                if (null === $value) {
                    $value = $this->createAttributeValue($bool);
                } else {
                    $value->setValue($bool);
                }
            }
        }

        return $value;
    }

    protected function load()
    {
        $ak = $this->getAttributeKey();
        if (!is_object($ak)) {
            return false;
        }

        $settings = $ak->getAttributeKeySettings();

        $this->akContent = $settings->getContent();
        $this->akShowTitle = $settings->getShowTitle();

        $this->set('akContent', $this->akContent);
        $this->set('akShowTitle', $this->akShowTitle);
    }
}
