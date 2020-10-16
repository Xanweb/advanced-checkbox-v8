<?php
use Concrete\Core\Editor\LinkAbstractor;

/* @var \Concrete\Core\Editor\CkeditorEditor $editor */
$editor->setAllowFileManager(false);
$editor->setAllowSitemap(true);
$editor->getPluginManager()->deselect(['table', 'underline', 'specialcharacters', 'sourcearea', 'image', 'sourcedialog']);
?>

<fieldset>
    <legend><?= t('Settings'); ?></legend>

    <div class="form-group">
        <div class="checkbox">
            <label>
                <?= $form->checkbox('akShowTitle', 1, $akShowTitle); ?>
                <?= t('Display question label'); ?>
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?= t("Checkbox content"); ?></label>
        <?= $editor->outputStandardEditor('akContent', LinkAbstractor::translateFromEditMode($akContent)); ?>
        <p class="help-block"><?= t('This will be displayed next to the checkbox. If it is blank, the name of the attribute will be displayed.');?></p>
    </div>
</fieldset>
