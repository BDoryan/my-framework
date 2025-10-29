<script
<?php if (!empty($type)) { ?>type="<?= $type ?>"<?php } ?>
src="<?= $src ?? '' ?>"
<?php if (!empty($integrity)) { ?>integrity="<?= $integrity ?>"<?php } ?>
<?php if (!empty($cross_origin)) { ?>crossorigin="<?= $cross_origin ?>" <?php } ?>
<?php if (!empty($async) && $async === true){ ?>async<?php } ?>
<?php if (!empty($defer) && $defer === true){ ?>defer<?php } ?>
><?= $content ?? '' ?></script>