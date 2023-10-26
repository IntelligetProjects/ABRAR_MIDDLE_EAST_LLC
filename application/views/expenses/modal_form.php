<?php echo form_open(get_uri("expenses/save"), array("id" => "expense-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div id="expense-dropzone" class="post-dropzone">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class=" form-group">
            <label for="expense_date" class=" col-md-3"><?php echo lang('date_of_expense'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "expense_date",
                    "name" => "expense_date",
                    "value" => $model_info->expense_date? $model_info->expense_date: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="category_id" class=" col-md-3"><?php echo lang('category'); ?></label>
            <div class=" col-md-9">
                <?php
                    echo form_dropdown("category_id", $categories_dropdown, $model_info->category_id, "class='select2 validate-hidden' id='category_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="item_id" class=" col-md-3"><?php echo lang('Item'); ?></label>
            <div class="col-md-9">
                <?php
                // $items_dropdown=[''=>'-'];
                  echo form_dropdown("item_id", $items_dropdown, $model_info->item_id, "class='select2 validate-hidden' id='item_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="title" class=" col-md-3"><?php echo lang('amount'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "amount",
                    "name" => "amount",
                    "value" => $model_info->amount ? to_decimal_format($model_info->amount) : "",
                    "class" => "form-control",
                    "placeholder" => lang('amount'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="tax_id" class=" col-md-3"><?php echo lang('tax'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("tax_id", $taxes_dropdown, array($model_info->tax_id), "class='select2'");
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="payment_mode" class=" col-md-3"><?php echo lang('payment_method'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("payment_mode", $modes_dropdown, $model_info->payment_mode, "class='select2 validate-hidden' id='payment_mode', data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        


        <div class="form-group" id="treasury">
            <label for="treasury" class=" col-md-3"><?php echo lang('cash_on_hand'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("treasury", $treasury, array($model_info->treasury), "class='select2 validate-hidden'  data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>

        <div id="cheque">

        <div class=" form-group">
            <label for="cheque_due_date" class=" col-md-3"><?php echo lang('cheque_due_date'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "cheque_due_date",
                    "name" => "cheque_due_date",
                    "value" => $model_info->cheque_due_date? $model_info->cheque_due_date: get_my_local_time("Y-m-d"),
                    "class" => "form-control",
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
                    <label for="cheque_number" class=" col-md-3"><?php echo lang('cheque_number'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "cheque_number",
                            "name" => "cheque_number",
                            "value" => $model_info->cheque_number ? $model_info->cheque_number : "",
                            "class" => "form-control",
                            "placeholder" => lang('cheque_number'),
                        ));
                        ?>
                    </div>
                </div>
        <div class="form-group">
                    <label for="cheque_account" class=" col-md-3"><?php echo lang('cheque_account'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "cheque_account",
                            "name" => "cheque_account",
                            "value" => $model_info->cheque_account ? $model_info->cheque_account : "",
                            "class" => "form-control",
                            "placeholder" => lang('cheque_account'),
                        ));
                        ?>
                    </div>
                </div>

        <!-- <div class="form-group">
                    <label for="title" class=" col-md-3"><?php echo lang('cheque_description'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "cheque_description",
                            "name" => "cheque_description",
                            "value" => $model_info->cheque_description ? $model_info->cheque_description : "",
                            "class" => "form-control",
                            "placeholder" => lang('cheque_description'),
                        ));
                        ?>
                    </div>
                </div> -->


        </div>
        <div class="form-group" id="bank" >
            <label for="bank" class=" col-md-3"><?php echo lang('Bank'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("bank", $banks_dropdown, array($model_info->bank), "class='select2 validate-hidden' id='bank_name' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                ?>
            </div>
        </div>
        <div class="form-group" id="pt_cash">
            <label for="pt_cash" class=" col-md-3"><?php echo lang('petty_cash_team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("pt_cash", $members_dropdown, $model_info->user_id ? $model_info->user_id : $this->login_user->id, "class='select2 validate-hidden' id='pt_cash_id', data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
        <!-- <div class=" form-group">
            <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "title",
                    "name" => "title",
                    "value" => $model_info->title,
                    "class" => "form-control",
                    "placeholder" => lang("title")
                ));
                ?>
            </div>
        </div> -->
        <div class="form-group">
            <label for="description" class=" col-md-3"><?php echo lang('description'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->description ? $model_info->description : "",
                    "class" => "form-control",
                    "placeholder" => lang('description'),
                    "data-rich-text-editor" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>

            </div>
        </div>

        <div class="form-group">
            <label for="expense_user_id" class=" col-md-3"><?php echo lang('team_member'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("expense_user_id", $members_dropdown, $model_info->user_id ? $model_info->user_id : $this->login_user->id, "class='select2 validate-hidden' id='expense_user_id', data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="expense_project_id" class=" col-md-3"><?php echo lang('project'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("expense_project_id", $projects_dropdown, $model_info->project_id, "class='select2 validate-hidden' id='expense_project_id'");
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="expense_client_id" class=" col-md-3"><?php echo lang('client'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("expense_client_id", $clients_dropdown, $model_info->client_id, "class='select2 validate-hidden' id='expense_client_id'");
                ?>
            </div>
        </div>

        <?php
        //TODO: for test accout only remove else block if changes in database ready for all clients
        if ($this->login_user->is_admin && (strcasecmp($this->db->dbprefix,'test_teamway') == 0 || strcasecmp($this->db->dbprefix,'tarteeb_v3') == 0 ))
        {
        ?>

         <!-- Domestic or import  -->
        <div class="form-group">
        <label for="expense_type" class=" col-md-3"><?php echo lang('expense_type'); ?></label>
        <div class=" col-md-9">
            <?php
            if($model_info->type === "domestic")
            $selected = 0;
            else if($model_info->type === "import")
            $selected = 1;
            else 
            $selected ='';
            echo form_dropdown("expense_type", array(""=>"-","domestic","import"), array($selected), "class='select2 validate-hidden' id='expense_type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>

        <div class="form-group">
            <label for="service_provider_id" class=" col-md-3"><?php echo lang('service_provider'); ?></label>
            <div class="col-md-7">
                <?php
                echo form_dropdown("service_provider_id", $service_provider_dropdown, $model_info->service_provider_id, "class='select2 validate-hidden' id='service_provider_id'");
                ?>
            </div>
            <?php if (!$model_info->service_provider_id) { ?>
                <div class="col-md-2">
                    <a class="btn btn-primary" id="add_service_provider"><?= lang("new")?></a>
                </div>
            <?php } ?>
        </div>

        <?php if (!$model_info->service_provider_id) { ?>
        <div id="new_service_provider" style="border: 0px solid; margin: 0px 0px 20px; padding: 10px">
            
            <div class="form-group">   
            

            <!-- name -->
            <div class="form-group">
                <label for="company_name" class="col-md-3"><?php echo lang('company_name'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "service_provider_name",
                        "name" => "service_provider_name",
                        "value" => "",
                        "class" => "form-control service_provider_data",
                        "placeholder" => lang('service_provider_name'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <!-- phone -->
            <div class="form-group">
                <label for="service_provider_phone" class="col-md-3"><?php echo lang('phone'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "service_provider_phone",
                        "name" => "service_provider_phone",
                        "value" => "",
                        "class" => "form-control service_provider_data",
                        "placeholder" => lang('phone'),
                    ));
                    ?>
                </div>
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label for="service_provider_email" class="col-md-3"><?php echo lang('email'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "service_provider_email",
                        "name" => "service_provider_email",
                        "value" => "",
                        "class" => "form-control service_provider_data",
                        "placeholder" => lang('email'),
                    ));
                    ?>
                </div>
            </div>

               <!-- VAT NUMBER -->
               <div class="form-group">
                <label for="service_provider_vat_number" class="col-md-3"><?php echo lang('vat_number'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "service_provider_vat_number",
                        "name" => "service_provider_vat_number",
                        "value" => "",
                        "class" => "form-control service_provider_data",
                        "placeholder" => lang('vat_number'),
                    ));
                    ?>
                </div>
            </div>

            <a class="btn btn-danger" id="close_service_provider" style="float: right;    margin-right: 10px;
            margin-bottom: 10px;"><?= lang("close")?></a> </div>
           
        </div>

        
        <?php } ?>

        <div class="form-group">
        <label for="invoice_ref_number" class=" col-md-3"><?php echo lang('invoice_reference_number'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "invoice_ref_number",
                "name" => "invoice_ref_number",
                "value" => $model_info->invoice_ref_number,
                "class" => "form-control",
                "placeholder" => lang('invoice_reference_number')
            ));
            ?>
        </div>
    </div>

       <!-- Domestic or import  -->

        <div class="form-group">
        <label for="expense_type" class=" col-md-3"><?php echo lang('expense_type'); ?></label>
        <div class=" col-md-9">
            <?php
            if($model_info->type === "domestic")
            $selected = 0;
            else if($model_info->type === "import")
            $selected = 1;
            else 
            $selected ='';
            echo form_dropdown("expense_type", array(""=>"-","domestic","import"), array($selected), "class='select2 validate-hidden' id='expense_type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>
        <?php } ?>
        
        <!-- <div class="form-group">
            <label for="tax_id" class=" col-md-3"><?php echo lang('second_tax'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2'");
                ?>
            </div>
        </div> -->

        <div class="form-group">
            <label class=" col-md-3"></label>
            <div class="col-md-9">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div>
        </div>

        <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

        <?php $this->load->view("includes/dropzone_preview"); ?>    
        <div class="modal-footer">
            <div class="row">
                <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class='fa fa-camera'></i> <?php echo lang("upload_file"); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {


        var uploadUrl = "<?php echo get_uri("expenses/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("expenses/validate_expense_file"); ?>";

        var dropzone = attachDropzoneWithForm("#expense-dropzone", uploadUrl, validationUrl);

        $("#expense-form").appForm({
            onSuccess: function (result) {
                if (typeof $EXPENSE_TABLE !== 'undefined') {
                    $EXPENSE_TABLE.appTable({newData: result.data, dataId: result.id});
                } else {
                    location.reload();
                }
            }
        });
        
        setDatePicker("#expense_date");
        setDatePicker("#cheque_due_date");

        $("#expense-form .select2").select2();

        
        $("#cheque").hide();
        $("#bank").hide();
        $("#treasury").hide();
        $("#pt_cash").hide();

       
        //load all 
        $("#payment_mode").on("change", function () {
            console.log($(this).val())
            if ($(this).val() == "cheque") {
                $("#cheque").show();
                $("#bank").show();
                $("#treasury").hide();
                $("#pt_cash").hide();
                $('#pt_cash select').attr('disabled','disabled');
                $('#treasury select').attr('disabled','disabled');
                $('#bank select').removeAttr('disabled');
            } else if ($(this).val() == "cash_on_hand") {
                $("#treasury").show();
                 $("#cheque").hide();
                 $("#bank").hide();
                $("#pt_cash").hide();
                $('#pt_cash select').attr('disabled','disabled');
                 $('#treasury select').removeAttr('disabled');
                $('#bank select').attr('disabled','disabled');
            }else if($(this).val() == "pt_cash"){
                $("#pt_cash").show();
                 $("#cheque").hide();
                 $("#bank").hide();
                 $("#treasury").hide();
                 $('#pt_cash select').removeAttr('disabled');
                $('#bank select').attr('disabled','disabled');
                $('#treasury select').attr('disabled','disabled');
            } else {
                $("#bank").show();
                $("#cheque").hide();
                $("#treasury").hide();
                $("#pt_cash").hide();
                $('#pt_cash select').attr('disabled','disabled');
                $('#treasury select').attr('disabled','disabled');
                $('#bank select').removeAttr('disabled');
            }
        });


        //Service provider
        $("#new_service_provider").hide();
        $("#add_service_provider").click(function (){
            console.log("dsddss");
                 $("#new_service_provider").show();
                 $("#service_provider_id").attr("data-rule-required", false);
                 $("#service_provider_id").attr("disabled", true);
                 
            });
        $("#close_service_provider").click(function (){
                $("#new_service_provider").hide();
                $("#service_provider_id").attr("data-rule-required", true);
                $("#service_provider_id").attr("disabled", false);
            });
        $("#invoice_client_id").change(function () {
                var provider = $("#invoice_client_id").val();
                if (provider) {
                    $("#add_service_provider").hide();
                    $("#new_service_provider .service_provider_data").attr("data-rule-required", false);
                } else {
                    $("#add_service_provider").show();
                }

                //alert ( $("#invoice_client_id").val());
        });

        $("#category_id").on("change", function () {
            console.log($(this).val());
            $.get(" <?php echo_uri('expenses/child_list?parent_id='); ?>"+$(this).val(), function(data) {
                data=jQuery.parseJSON(data)
                console.log('hello');
            if(data.success){
            $('#item_id').html('');
            $('#item_id').html(data.data);
            }else{
                $('#item_id').html('');
                $('#item_id').html('<option></option>');   
            }
            });
        });

        $('#category_id').change();
        $('#payment_mode').change();
        

    });
</script>