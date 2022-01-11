<form action="" method="post" enctype="multipart/form-data">
    <div class="input-group">
        <div class="input-group-btn">
            <input type='hidden' name='step' value='1'>
            <select name='version' class="btn btn-default">
                <option value=''>Select version</option>
                <?php foreach ($config['versions'] as $version) { ?>
                <option value='<?= $version ?>'><?= $version ?></option>
                <?php } ?>
            </select>
        </div>
        &nbsp;<input type="file" data-filename-placement="inside" title="Search for a migration file to upload" class="btn btn-default" name="file" >
        &nbsp;<input type='submit' value='Submit' class='btn btn-primary'>
    </div>
</form>
