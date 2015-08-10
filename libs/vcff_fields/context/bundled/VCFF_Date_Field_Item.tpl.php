<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-dat-field <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <?php do_action('vcff_field_pre_label',$this); ?>
    <label><?php echo $field_label; ?><?php if ($this->Is_Required()): ?> <span class="required">*</span><?php endif; ?></label>
    <?php do_action('vcff_field_post_label',$this); ?>
    <div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <div class="simple-data-field <?php echo $field_extra_class; ?>">
        <?php if ($date_format == 'dd/mm/yyyy'): ?>
        <select name="<?php echo $machine_code; ?>[day]" class="form-control field-day <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
            <option value="">dd</option>
            <?php for ($d=1;$d<=31;$d++): ?>
            <option value="<?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?></option>
            <?php endfor; ?>
        </select>
        <?php endif; ?>
        <select name="<?php echo $machine_code; ?>[month]" class="form-control field-month <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
            <option value="">mm</option>
            <?php for ($m=1;$m<=12;$m++): ?>
            <option value="<?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?> - <?php echo date('F',mktime(0,0,0,$m)) ?></option>
            <?php endfor; ?>
        </select>
        <?php if ($date_format == 'mm/dd/yyyy'): ?>
        <select name="<?php echo $machine_code; ?>[day]" class="form-control field-day <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
            <option value="">dd</option>
            <?php for ($d=1;$d<=31;$d++): ?>
            <option value="<?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?></option>
            <?php endfor; ?>
        </select>
        <?php endif; ?>
        <select name="<?php echo $machine_code; ?>[year]" class="form-control field-year <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
            <option value="">yyyy</option>
            <?php $years = $this->Get_Years(); ?>
            <?php foreach ($years as $k => $year):?>
            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php do_action('vcff_field_post_input',$this); ?>
</div>