<?php defined('C5_EXECUTE') or die("Access Denied.");
use Concrete\Core\Editor\LinkAbstractor;

?>

<div class="checkbox">
    <label>
        <input type="checkbox" value="1" name="<?php echo $view->field('value'); ?>" <?php if ($checked) {
    ?> checked <?php
} ?> >
        <?php echo LinkAbstractor::translateFrom($controller->getCheckboxLabel()); ?>
    </label>
</div>
