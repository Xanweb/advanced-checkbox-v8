<?php
namespace Concrete\Package\FormAdvancedCheckbox\Entity\Attribute\Key\Settings;

use Doctrine\ORM\Mapping as ORM;
use Concrete\Core\Entity\Attribute\Key\Settings\Settings as SettingsBase;

/**
 * @ORM\Entity
 * @ORM\Table(name="atAdvancedCheckboxSettings")
 */
class AdvancedCheckboxSettings extends SettingsBase
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $akContent = '';

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $akShowTitle = false;

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->akContent;
    }

    /**
     * @return string
     */
    public function getShowTitle()
    {
        return $this->akShowTitle;
    }

    /**
     * @return string
     */
    public function supportsTitle()
    {
        return $this->akShowTitle;
    }

    /**
     * @param string $akContent
     */
    public function setContent($akContent)
    {
        $this->akContent = $akContent;
    }

    /**
     * @param bool $akShowTitle
     */
    public function setShowTitle($akShowTitle)
    {
        $this->akShowTitle = $akShowTitle;
    }

    public function getAttributeTypeHandle()
    {
        return 'advanced_checkbox';
    }
}
