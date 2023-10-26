<div class="tab-content">
    <?php echo form_open(get_uri("team_members/save_general_info/" . $user_info->id), array("id" => "general-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4> <?php echo lang('general_info'); ?></h4>
        </div>
        <?php //print_r($users_ddl); ?>
        <div class="panel-body">
            <div class="form-group">
                <label for="first_name" class=" col-md-2"><?php echo lang('first_name'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "first_name",
                        "name" => "first_name",
                        "value" => $user_info->first_name,
                        "class" => "form-control",
                        "placeholder" => lang('first_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class=" col-md-2"><?php echo lang('last_name'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "last_name",
                        "name" => "last_name",
                        "value" => $user_info->last_name,
                        "class" => "form-control",
                        "placeholder" => lang('last_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required")
                    ));
                    ?>
                </div>
            </div>
            <?php  
            $nationalitys = $this->Nationality_model->get_all()->result();
            foreach ($nationalitys as $nationality) {
                $nationality_dropdown[$nationality->title] = $nationality->title;
            }
            // return $nationality_dropdown;
        ?>
            <div class="form-group">
                <label for="superior" class=" col-md-2"><?php echo lang("nationality") ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_dropdown("nationality", $nationality_dropdown, $user_info->nationality, "class='select2 validate-hidden' id='superior_id' , data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                    ?>
                </div>    



            </div>
            <div class="form-group">
                <label for="address" class=" col-md-2"><?php echo lang('E-mail Address'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_textarea(array(
                        "id" => "address",
                        "name" => "address",
                        "value" => $user_info->address,
                        "class" => "form-control",
                        "placeholder" => lang('E-mail Address')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_address" class=" col-md-2"><?php echo lang('Alternative E-mail'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_textarea(array(
                        "id" => "alternative_address",
                        "name" => "alternative_address",
                        "value" => $user_info->alternative_address,
                        "class" => "form-control",
                        "placeholder" => lang('Alternative E-mail')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class=" col-md-2"><?php echo lang('phone'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                        "value" => $user_info->phone,
                        "class" => "form-control",
                        "placeholder" => lang('phone')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="alternative_phone" class=" col-md-2"><?php echo lang('alternative_phone'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "alternative_phone",
                        "name" => "alternative_phone",
                        "value" => $user_info->alternative_phone,
                        "class" => "form-control",
                        "placeholder" => lang('alternative_phone')
                    ));
                    ?>
                </div>
            </div>
            <!-- <div class="form-group">
                <label for="skype" class=" col-md-2">Skype</label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "skype",
                        "name" => "skype",
                        "value" => $user_info->skype ? $user_info->skype : "",
                        "class" => "form-control",
                        "placeholder" => "Skype"
                    ));
                    ?>
                </div>
            </div> -->
            <div class="form-group">
                <label for="resident_card_no" class=" col-md-2"><?= lang('resident_card_no')?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "resident_card_no",
                        "name" => "resident_card_no",
                        "value" => $user_info->resident_card_no ? $user_info->resident_card_no : "",
                        "class" => "form-control",
                        "placeholder" => lang("resident_card_no")
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="resident_card_expiry" class=" col-md-2"><?= lang('resident_card_expiry')?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "resident_card_expiry",
                        "name" => "resident_card_expiry",
                        "value" => $user_info->resident_card_expiry ? $user_info->resident_card_expiry : "",
                        "class" => "form-control",
                        "placeholder" => lang("resident_card_expiry")
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="passport_no" class=" col-md-2"><?= lang('passport_no')?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "passport_no",
                        "name" => "passport_no",
                        "value" => $user_info->passport_no ? $user_info->passport_no : "",
                        "class" => "form-control",
                        "placeholder" => lang("passport_no")
                    ));
                    ?>
                </div>
            </div>


            <div class="form-group">
                <label for="passport_expiry" class=" col-md-2"><?= lang('passport_expiry')?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "passport_expiry",
                        "name" => "passport_expiry",
                        "value" => $user_info->passport_expiry ? $user_info->passport_expiry : "",
                        "class" => "form-control",
                        "placeholder" => lang("passport_expiry")
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="dob" class=" col-md-2"><?php echo lang('date_of_birth'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "dob",
                        "name" => "dob",
                        "value" => $user_info->dob,
                        "class" => "form-control",
                        "placeholder" => lang('date_of_birth'),
                        "autocomplete" => "off"
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class=" col-md-2"><?php echo lang('gender'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_radio(array(
                        "id" => "gender_male",
                        "name" => "gender",
                        "data-msg-required" => lang("field_required"),
                            ), "male", ($user_info->gender === "female") ? false : true);
                    ?>
                    <label for="gender_male" class="mr15"><?php echo lang('male'); ?></label> <?php
                    echo form_radio(array(
                        "id" => "gender_female",
                        "name" => "gender",
                        "data-msg-required" => lang("field_required"),
                            ), "female", ($user_info->gender === "female") ? true : false);
                    ?>
                    <label for="gender_female" class=""><?php echo lang('female'); ?></label>
                </div>
            </div>


            <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-2", "field_column" => " col-md-10")); ?> 

        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#general-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                setTimeout(function () {
                    window.location.href = "<?php echo get_uri("team_members/view/" . $user_info->id); ?>" + "/general";
                }, 500);
            }
        });
        $("#general-info-form .select2").select2();

        setDatePicker("#dob");
        setDatePicker("#resident_card_expiry");
        setDatePicker("#passport_expiry");
    });
</script>    