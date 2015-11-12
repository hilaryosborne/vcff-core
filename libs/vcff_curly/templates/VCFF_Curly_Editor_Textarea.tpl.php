<div class="form-horizontal vcff-tag-editor">
    <div class="row tag-controls">
        <div class="col-sm-8">
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
        <div class="col-sm-4">
            <button type="button" class="btn btn-default tag-insert">Insert</button>
        </div>
    </div>
    <div class="tag-editor">
        <textarea placeholder="Enter the alert contents (HTML or plain text)" name="<?php echo $machine_code; ?>" class="form-control tag-editor-field"><?php echo $field_value; ?></textarea>
    </div>
    <p class="hints"><strong>Basic HTML Tags Allowed.</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus non ultricies nunc, eu luctus diam. Morbi elementum fermentum velit, et laoreet dolor viverra maximus. Donec in risus quis nibh efficitur porttitor.</p>
</div>