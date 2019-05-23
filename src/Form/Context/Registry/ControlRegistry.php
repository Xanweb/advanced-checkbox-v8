<?php
namespace Concrete\Package\FormAdvancedCheckbox\Form\Context\Registry;

use Concrete\Core\Form\Context\Registry\ControlEntry;
use Concrete\Core\Form\Context\Registry\ControlRegistry as CoreControlRegistry;

class ControlRegistry extends CoreControlRegistry
{
    protected function addOrReplaceEntry(ControlEntry $entry)
    {
        $index = -1;
        foreach ($this->entries as $key => $existingEntry) {
            if ($entry->getHandle() == $existingEntry->getHandle()) {
                if (get_class($existingEntry->getContext()) == get_class($entry->getContext())) {
                    $index = $key;
                }
            }
        }
        if ($index >= 0) {
            $this->entries[$index] = $entry;
        } else {
            $this->entries[] = $entry;
        }
    }
}
