<form action="" method="post" enctype="multipart/form-data">
    <div class="input-group">
        <input type='hidden' name='step' value='2'>
        <input type='hidden' name='file' id='file' value='<?=$file_name; ?>'>
        <?php foreach($c_config as $company) { ?>
        <div class="input-group">
            <span class="input-group-addon">
                <input type="checkbox" name="migrate[<?=$company['company_id'] ?>]" 
                       value="<?=$company['company_id'] ?>"
                       <?=($company['checked'])?'checked="checked"':'disabled="disabled"'?>>
                <?=$company['version'] ?>
            </span>
            <div class="form-control" style='width:400px'>
                <?=$company['database'] ?>
            </div>
        </div>
        <br>
        <?php } ?>
        <input type='submit' value='Submit' class='btn btn-primary'>
    </div>
</form>

