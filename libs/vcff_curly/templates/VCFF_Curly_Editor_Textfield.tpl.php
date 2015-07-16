<div class="vcff-tag-editor">
    <div class="row">
        <div class="col-sm-5">
            <input name="<?php echo $machine_code; ?>" type="text" value="<?php echo $field_value; ?>" class="form-control tag-editor-field">
        </div>
        <div class="col-sm-4">
            <select class="form-control tag-list">
                <?php $hints = $this->Get_Hints_List(); ?>
                <?php foreach ($hints as $category => $tags): ?>
                    <optgroup label="<?php echo $category; ?>">
                    <?php foreach ($tags as $code => $tag): ?>
                    <option value="<?php echo $tag['hint']; ?>"><?php echo $tag['name']; ?></option>
                    <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-1">
            <button type="button" class="btn btn-default tag-insert">Insert</button>
        </div>
    </div>
</div>