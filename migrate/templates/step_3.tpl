<form action="" method="post" enctype="multipart/form-data">
    <input type='hidden' id='file' value='<?= $file_name ?>'>
    <input type='hidden' name='step' value='3'>
</form>
<div class="worker-content">Loading...</div>
<div class="listener-table"></div>
<div class="listener-content"></div>
<script>
    companies = Array();
<?php $i = 0; ?>
<?php foreach ($companies as $id => $company) { ?>
        companies[<?= $i++ ?>] = <?= $company ?>;
<?php } ?>
</script>
<script src="./static/worker.js"></script>