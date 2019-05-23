<?php
namespace Concrete\Package\FormAdvancedCheckbox\Entity\Attribute\Value\Value;

use Doctrine\ORM\Mapping as ORM;
use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;

/**
 * @ORM\Entity
 * @ORM\Table(name="atAdvancedCheckbox")
 */
class AdvancedCheckboxValue extends AbstractValue
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $value = false;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return ($this->value) ? t('I agree') : t("I don't agree");
    }
}
