<?php if ($categories = \Shop\Models\Categories::find()) { ?>
<div class="max-height-200 list-group-item">
    
    <?php $current = \Dsc\ArrayHelper::getColumn( (array) $flash->old('categories'), 'id' ); ?>
    <?php foreach ($categories as $one) { ?>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="category_ids[]" class="icheck-input" value="<?php echo $one->_id; ?>" <?php if (in_array($one->_id, $current)) { echo "checked='checked'"; } ?>>
            <?php echo @str_repeat( "&ndash;", substr_count( @$one->path, "/" ) - 1 ) . " " . $one->title; ?>
        </label>
    </div>
    <?php } ?> 
    
</div>
<?php } ?>