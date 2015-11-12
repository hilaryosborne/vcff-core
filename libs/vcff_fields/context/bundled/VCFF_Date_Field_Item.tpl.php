<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-date-field <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <?php do_action('vcff_field_pre_label',$this); ?>
    <label><?php echo $field_label; ?><?php if ($this->Is_Required()): ?> <span class="required">*</span><?php endif; ?></label>
    <?php do_action('vcff_field_post_label',$this); ?>
    <div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <?php if ($display_mode == 'select_el'): ?>
    <div class="<?php echo $field_extra_class; ?>">
        <select name="<?php echo $machine_code; ?>[dd]" class="form-control field-day <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
            <option value="">dd</option>
            <?php $_posted_day = $this->_Get_Date_Day(); ?>
            <?php for ($d=1;$d<=31;$d++): ?>
            <option <?php if ($_posted_day && $_posted_day == str_pad($d,2,'0',STR_PAD_LEFT)): ?>selected="selected"<?php endif; ?> value="<?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($d,2,'0',STR_PAD_LEFT); ?></option>
            <?php endfor; ?>
        </select>
        <select name="<?php echo $machine_code; ?>[mm]" class="form-control field-month <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
            <option value="">mm</option>
            <?php $_posted_month = $this->_Get_Date_Month(); ?>
            <?php for ($m=1;$m<=12;$m++): ?>
            <option <?php if ($_posted_month && $_posted_month == str_pad($m,2,'0',STR_PAD_LEFT)): ?>selected="selected"<?php endif; ?> value="<?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?>"><?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?> - <?php echo date('F',mktime(0,0,0,$m)) ?></option>
            <?php endfor; ?>
        </select>
        <select name="<?php echo $machine_code; ?>[yyyy]" class="form-control field-year <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
            <option value="">yyyy</option>
            <?php $_posted_year = $this->_Get_Date_Year(); ?>
            <?php $_select_years = $this->_Select_Get_Years(); ?>
            <?php foreach ($_select_years as $k => $year):?>
            <option <?php if ($_posted_year && $_posted_year == $year): ?>selected="selected"<?php endif; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php else: ?>
    <?php $_max_date = $this->_Get_Max_Date(false); ?>
    <?php $_min_date = $this->_Get_Min_Date(false); ?>
    <input type="date" name="<?php echo $machine_code; ?>" <?php echo $attributes; ?> value="<?php echo $this->posted_value ? $this->posted_value : $default_value; ?>" <?php if ($_max_date): ?>max="<?php echo $_max_date->format('Y-m-d'); ?>"<?php endif; ?> <?php if ($_min_date): ?>max="<?php echo $_min_date->format('Y-m-d'); ?>"<?php endif; ?> <?php if ($is_disabled == 'yes'): ?> disabled="disabled"<?php endif; ?> class="form-control <?php if ($this->Has_Dependents()): ?>key-change check-change<?php endif; ?> <?php echo $field_extra_class; ?>">
    <?php endif; ?>
    <?php do_action('vcff_field_post_input',$this); ?>
</div>