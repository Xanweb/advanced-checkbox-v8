<?php
use Concrete\Core\Editor\LinkAbstractor;

/* @var \Concrete\Core\Editor\CkeditorEditor $editor */
$editor->setAllowFileManager(false);
$editor->setAllowSitemap(true);
$editor->getPluginManager()->deselect(['table', 'underline', 'specialcharacters', 'sourcearea', 'image', 'sourcedialog']);
?>

<fieldset>
    <legend><?php echo t('Settings'); ?></legend>

    <div class="form-group">
        <div class="checkbox">
            <label>
                <?php echo $form->checkbox('akShowTitle', 1, $akShowTitle); ?>
                <?php echo t('Display question label'); ?>
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo t("Checkbox content"); ?></label>
        <?php echo $editor->outputStandardEditor('akContent', LinkAbstractor::translateFromEditMode($akContent)); ?>
        <p class="help-block"><?php echo t('This will be displayed next to the checkbox. If it is blank, the name of the attribute will be displayed.');?></p>
    </div>

</fieldset>
