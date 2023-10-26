<div id="page-content" class="p20 clearfix">
    <div class="row">
        <div id="sidebarSettings" class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "accounting";
            $this->load->view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_accounting_settings"), array("id" => "accounting-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="panel">
                <div class="panel-default panel-heading">
                    <h4><?php echo lang("accounting_settings"); ?></h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class=" col-md-12"><?php echo lang('set_each_accounting_setting_to_the_corresponding_account_to_map_the_system_operations_to_the_correct_account'); ?></label>
                    </div>

                    <!-- <div class="form-group">
                        <label for="financial_year_end" class=" col-md-2"><?php echo lang('financial_year_end'); ?></label>
                        <div class=" col-md-10">
                            <?php
                            // echo form_input(array(
                            //     "id" => "financial_year_end",
                            //     "name" => "financial_year_end",
                            //     "value" => get_setting("financial_year_end"),
                            //     "class" => "form-control",
                            //     "placeholder" => lang('financial_year_end'),
                            //     "data-rule-required" => true,
                            //     "data-msg-required" => lang("field_required")
                            // ));
                            ?>
                        </div>
                    </div> -->

                    <?php 
                    // $sets = array("default_bank", "clients_accounts_parent","suppliers_accounts_parent","banks_accounts_parent","cash_on_hand_accounts_parent","expenses_accounts_parent","default_cash_on_hand", "payable_cheques", "receivable_cheques", "default_inventory", "cost_of_goods_sold","default_sales", "discount","VAT_in", "VAT_out","VAT_expense"); 
                    $sets = array( "clients_accounts_parent","suppliers_accounts_parent","banks_accounts_parent","cash_on_hand_accounts_parent","expenses_accounts_parent","petty_cash_parent","default_git", "payable_cheques", "receivable_cheques", "default_inventory", "discount","VAT_in", "VAT_out"); 
                    ?>
                    <?php foreach ($sets as $lc) {?>
                        <?php //echo $lc ?>
                        <div class="form-group">
                            <label for="<?= $lc ?>" class=" col-md-2"><?php if($lc=='VAT_in'){ echo lang('VAT_out'); }elseif($lc=='VAT_out'){ echo lang('VAT_in'); }else{ echo lang($lc); }?></label>
                            <!-- <label for="<?= $lc ?>" class=" col-md-2"><?php echo lang($lc); ?></label> -->
                            <div class="col-md-10">
                                  <input type="text" value= '<?= get_setting($lc); ?>' name="<?= $lc ?>"  id='<?= $lc."_dropdown" ?>' class="dropdownA w100p validate-hidden"  data-rule-required="true" data-msg-required="<?= lang('field_required'); ?>" placeholder="<?= lang($lc); ?>"  />    
                            </div>
                        </div>
                    <?php } ?>

                    <?php //$sets = array('salary_expenses', 'leave_salary_expenses', 'payable_salaries',   'payable_PASI',  'salary_advances'); ?>
                    <?php $sets = array('salary_expenses',  'payable_salaries',   'payable_PASI',  'salary_advances'); ?>
                    <?php foreach ($sets as $lc) {?>
                        <div class="form-group">
                            <label for="<?= $lc ?>" class=" col-md-2"><?php echo lang($lc); ?></label>
                            <div class="col-md-10">
                                  <input type="text" value= '<?= get_setting($lc); ?>' name="<?= $lc ?>"  id='<?= $lc."_dropdown" ?>' class="dropdownA w100p validate-hidden"  data-rule-required="true" data-msg-required="<?= lang('field_required'); ?>" placeholder="<?= lang($lc); ?>"  />    
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        //$("#accounting-settings-form").select2(); 

        setDatePicker("#financial_year_end");

        /*$("#cash_accounts_dropdown").select2({
            multiple: true,
            data: <?php echo ($accounts_dropdown); ?>
        });*/

        $(".dropdownA").select2({
            data: <?php echo ($accounts_dropdown); ?>
        });

    });
</script>