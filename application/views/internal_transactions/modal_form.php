<?php echo form_open(get_uri("internal_transactions/save"), array("id" => "internal_transactions-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class=" form-group">
            <label for="date" class=" col-md-3"><?php echo lang('date_of_transaction'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "date",
                    "name" => "date",
                    "value" => $model_info->date,
                    "class" => "form-control",
                    "placeholder" => "YYYY-MM-DD",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>

          <div class="form-group">
            <label for="from" class=" col-md-3"><?php echo lang('from'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("from_employee", $from_dropdown, $model_info->from_employee, "class='select2 validate-hidden' id='from' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
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
        <div class="form-group" id="bank">
            <label for="bank" class=" col-md-3"><?php echo lang('Bank'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("bank", $banks_dropdown, array($model_info->bank), "class='select2 validate-hidden' id='bank_name' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                ?>
            </div>
        </div>
 <div class="form-group">
            <label for="to" class=" col-md-3"><?php echo lang('to'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("to_employee", $to_dropdown, $model_info->to_employee, "class='select2 validate-hidden' id='to' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>
        </div>
       
    <div class="form-group">
        <br />
        <label for="amount" class=" col-md-3"><?php echo lang('amount'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info->amount,
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <br />
    </div>
    <div class="form-group">
        <label for="note" class="col-md-3"><?php echo lang('note'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "note",
                "name" => "note",
                "value" => $model_info->note ? $model_info->note : "",
                "class" => "form-control",
                "placeholder" => lang('note')
            ));
            ?>
        </div>
    </div>

<!--     <?php if ($this->login_user->is_admin) { ?>
    <div class="form-group">
        <label for="from" class=" col-md-3"><?php echo lang('status'); ?></label>
        <div class=" col-md-9">
            <?php
                $status = $model_info->status ? $model_info->status : 'draft'; 

            echo form_dropdown("status", $status_dropdown, $status, "class='select2 validate-hidden' id='from' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>
    <?php } else { ?>
         <?php
            $status = $model_info->status ? $model_info->status : 'draft'; 
            echo form_input(array(
                "id" => "stat",
                "name" => "status",
                "value" => $status,
                "class" => "form-control hide",
                "placeholder" => lang('status'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
    <?php } ?>
</div> -->

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#internal_transactions-form").appForm({
            onSuccess: function(result) {
                $("#internal_transactions-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });

    setDatePicker("#date");
    $("#internal_transactions-form .select2").select2();


        $("#bank").hide();
        $("#treasury").hide();

        //load all 
        $("#from").on("change", function () {
            console.log($(this).val());
            if ($(this).val() == "0") {
                $("#treasury").show();
                 $("#bank").hide();
                 $('#treasury select').removeAttr('disabled');
                $('#bank select').attr('disabled','disabled');
            }else if ($(this).val() == "999") {
                $("#bank").show();
                $("#treasury").hide();
                $('#treasury select').attr('disabled','disabled');
                $('#bank select').removeAttr('disabled');
            }else{
                $("#bank").hide();
                $("#treasury").hide(); 
                $('#treasury select').attr('disabled','disabled');
                $('#bank select').attr('disabled','disabled');
            }
        });
</script>