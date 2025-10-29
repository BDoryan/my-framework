<link
        <?php if (!empty($integrity)) { ?>integrity="<?= $integrity ?>"<?php } ?>
        <?php if (!empty($cross_origin)) { ?>crossorigin="<?= $cross_origin ?>"
<?php } ?>
        href="<?= $href ?? '' ?>"
        <?php if (!empty($rel)) { ?>rel="<?= $rel ?>"<?php }
if (!empty($type)) { ?> type="<?= $type ?>"<?php } ?>
>

