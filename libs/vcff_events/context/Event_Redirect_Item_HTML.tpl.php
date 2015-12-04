<p>You are being redirected, please wait <a href="" class="redirect-backup">Click here if you are not redirected</a></p>
<form id="VCFF_MiddleMan_Form" action="<?php echo $post_url; ?>" method="post">
    <?php if ($hidden_fields && is_array($hidden_fields)): ?>
    <?php foreach ($hidden_fields as $machine_code => $field_value): ?>
    <input type="hidden" name="<?php echo $machine_code; ?>" value="<?php echo $field_value; ?>">
    <?php endforeach; ?>
    <?php endif; ?>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('#VCFF_MiddleMan_Form').submit();
    });

    $('.redirect-backup').click(function(){
        $('#VCFF_MiddleMan_Form').submit();
    });
</script>