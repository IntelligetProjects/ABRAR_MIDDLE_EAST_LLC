<div class="tab-content">
    <?php echo form_open(get_uri("team_members/save_job_info/"), array("id" => "job-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>

    <input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('job_info'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="job_title" class=" col-md-2"><?php echo lang('job_title'); ?></label>
                <div class="col-md-10">
                    <?php
                //    echo $show_job_info;die();
                    // echo $this->login_user->is_admin; die();
                    if(!$this->login_user->is_admin){
                        // $readonly=array('readonly' =>'true');
                        $readonly='readonly';
                        $value='true';
                    }else{
                        $readonly=null; 
                        $value=null;
                    }
                    if($show_job_info){
                        $readonly=null; 
                        $value=null;
                    }
                    // echo var_dump($readonly); die();
                    echo form_input(array(
                        "id" => "job_title",
                        "name" => "job_title",
                        "value" => $job_info->job_title,
                        "class" => "form-control",
                        "placeholder" => lang('job_title'),
                        $readonly =>$value
                        // $readonly
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="salary" class=" col-md-2"><?php echo lang('salary'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary",
                        "name" => "salary",
                        "value" => $job_info->salary ? to_decimal_format($job_info->salary) : "",
                        "class" => "form-control",
                        "placeholder" => lang('salary'), 
                        $readonly =>$value

                    ));
                    ?>
                </div>
            </div>
            <!-- <div class="form-group">
                <label for="salary_term" class=" col-md-2"><?php echo lang('salary_term'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "salary_term",
                        "name" => "salary_term",
                        "value" => $job_info->salary_term,
                        "class" => "form-control",
                        "placeholder" => lang('salary_term'),
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div> -->

            <div class="form-group">
                <label for="working_hours" class=" col-md-2"><?php echo lang('working_hours'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "working_hours",
                        "name" => "working_hours",
                        // "value" => $job_info->working_hours ? to_decimal_format($job_info->working_hours) : "",
                        "value" => $job_info->working_hours ,
                        "class" => "form-control",
                        "placeholder" => lang('working_hours'),
                        "type" => "number",
                        "min" => 0,
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="yearly_leaves" class=" col-md-2"><?php echo lang('yearly_leaves'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "yearly_leaves",
                        "name" => "yearly_leaves",
                        "value" => $job_info->yearly_leaves ? to_decimal_format($job_info->yearly_leaves) : "",
                        "class" => "form-control",
                        "placeholder" => lang('days'),
                        "type" => "number",
                        "min" => 0,
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="housing" class=" col-md-2"><?php echo lang('housing_allowance'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "housing",
                        "name" => "housing",
                        "value" => $user_info->housing ? bcdiv($user_info->housing,1,3) : "",
                        "class" => "form-control",
                        "placeholder" => lang('housing_allowance'),
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="transportation" class=" col-md-2"><?php echo lang('transportation_allowance'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "transportation",
                        "name" => "transportation",
                        "value" => $user_info->transportation ? bcdiv($user_info->transportation,1,3) : "",
                        "class" => "form-control",
                        "placeholder" => lang('transportation_allowance'),
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="telephone" class=" col-md-2"><?php echo lang('telephone_allowance'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "telephone",
                        "name" => "telephone",
                        "value" => $user_info->telephone ? bcdiv($user_info->telephone,1,3) : "",
                        "class" => "form-control",
                        "placeholder" => lang('telephone_allowance'),
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="utility" class=" col-md-2"><?php echo lang('utility_allowance'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "utility",
                        "name" => "utility",
                        "value" => $user_info->utility ? bcdiv($user_info->utility,1,3) : "",
                        "class" => "form-control",
                        "placeholder" => lang('utility_allowance'),
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="national" class=" col-md-2"><?php echo lang('nationality'); ?></label>
                <div class="col-md-10">
                    <?php
                    $nationalities = array("0" => "Expatriates") + array("1" => "Omani National");
                    echo form_dropdown("national", $nationalities, $user_info->national, "class='select2 validate-hidden' id='national' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="pasi" class=" col-md-2"><?php echo lang('enable_pasi_job_security'); ?><br><i>[This option works only for OMANI Nationals]</i></label>
                
                <div class="col-md-10">
                    <?php
                    $pasi = array("1" => "enabled") + array("0" => "disabled");
                    echo form_dropdown("pasi", $pasi, $user_info->pasi, "class='select2 validate-hidden' id='pasi' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="date_of_hire" class=" col-md-2"><?php echo lang('date_of_hire'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "date_of_hire",
                        "name" => "date_of_hire",
                        "value" => $user_info->date_of_hire,
                        "class" => "form-control",
                        "placeholder" => lang('date_of_hire'),
                        "autocomplete" => "off",
                        $readonly =>$value
                    ));
                    ?>
                </div>
            </div>

            <!-- <div class="form-group">
                <label for="bank_title" class=" col-md-2"><?php echo lang('bank_title'); ?></label>
                <div class="col-md-10">
                    <?php
                    // $banks = array("bank_muscat" => lang("bank_muscat") ) + 
                    // array("bank_sohar" => lang("bank_sohar") ) + 
                    // array("bank_dhofar" => lang("bank_dhofar") ) + 
                    // array("nbo" => lang("nbo") ) + 
                    // array("oman_arab_bank" => lang("oman_arab_bank") ) + 
                    // array("hsbc" => lang("hsbc") ) + 
                    // array("ahli_bank" => lang("ahli_bank") ) + 
                    // array("bank_nizwa" => lang("bank_nizwa") ) + 
                    // array("alizz_islamic_bank" => lang("alizz_islamic_bank") );
                    // echo form_dropdown("bank_title", $banks, $user_info->bank_title, "class='select2 validate-hidden' id='national' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                    ?>
                </div>
            </div> -->
            <div class="form-group">
                <label for="bank_title" class=" col-md-2"><?php echo lang('bank_title'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "bank_title",
                        "name" => "bank_title",
                        "value" => $user_info->bank_title,
                        "class" => "form-control",
                        "placeholder" => lang('bank_title'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="account_title" class=" col-md-2"><?php echo lang('bank_account_title'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "account_title",
                        "name" => "account_title",
                        "value" => $user_info->account_title,
                        "class" => "form-control",
                        "placeholder" => lang('account_title'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="account_no" class=" col-md-2"><?php echo lang('bank_account_no'); ?></label>
                <div class="col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "account_no",
                        "name" => "account_no",
                        "value" => $user_info->account_no,
                        "class" => "form-control",
                        "placeholder" => lang('bank_account_no'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php if ($this->login_user->is_admin||$show_job_info) { ?>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        <?php } ?>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#job-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                window.location.href = "<?php echo get_uri("team_members/view/" . $job_info->user_id); ?>" + "/job_info";
            }
        });
        $("#job-info-form .select2").select2();
        $("#national").select2();

        setDatePicker("#date_of_hire");

    });
</script>    