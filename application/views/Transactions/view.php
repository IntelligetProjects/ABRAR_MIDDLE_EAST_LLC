<style>
.select2-drop {
  width: 300px !important;
}
</style>
<div id="page-content" class="clearfix p20">
    <?php if ($this->login_user->role_id==1 ||$this->login_user->is_admin && !strcasecmp($this->db->dbprefix, 'Integrated_Banners_') == 0) { ?>
        <?php echo form_open(get_uri("transactions/enteries_save"), array("id" => "todo-inline-form", "class" => "", "role" => "form")); ?>
        <input type='hidden' name="transaction_id" value="<?php echo $transactions_info->id; ?>" />
        <div class="todo-input-box" style = "width: 100%; margin: 10px auto 30px;     max-width: none;">
        <?php if ($transactions_info->type == "payment_voucher" || $transactions_info->type == "receipt_voucher") { ?>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    echo form_dropdown("bank_cash", $banks, array($transactions_info->bank_cash), "class='select2 validate-hidden' id='bank_cash' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                    ?>
                </div>
                <div class="col-md-3">
                     <span class="mr10"><h3><?php echo lang($transactions_info->type)." ".$transactions_info->id; ?></h3></span>
                </div>
            </div>
            
        <?php } ?> 
        <div class="row">
            <div class="col-md-3">
                <?php
                echo form_input(array(
                    "id" => "account",
                    "name" => "account",
                    "class" => "form-control",
                    "placeholder" => lang("account"),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
                <a id="account_parent_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
            </div>


            <div class="col-md-1">
                <?php
                echo form_dropdown("type", $type_dropdown, $transactions_info->type, "class='select2 validate-hidden' id='type' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
                ?>
            </div>

            <div class="col-md-2">
                <?php
                echo form_input(array(
                    "id" => "amount",
                    "name" => "amount",
                    "class" => "form-control",
                    "placeholder" => "Amount",
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                    "min" => 0,
                    "type" => "number",
                ));
                ?>
            </div>

            <div class="col-md-6">
                <?php
                echo form_input(array(
                    "id" => "narration",
                    "name" => "narration",
                    "class" => "form-control",
                    "placeholder" => lang("narration"),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>


            </div>
        </div>
        <div class="row">

            
            

            <div class="col-md-2">
                <?php
                    echo form_dropdown("concerned_person", $concerned_persons_dropdown, "", "class='select2 validate-hidden' id='concerned_person'");
                ?>
            </div>


            
            <div class="col-md-3">
                <?php
                    $ref_array = array(
                        "id" => "reference",
                        "name" => "reference",
                        "value" => "",
                        "class" => "form-control validate-hidden",
                        "placeholder" => lang('reference'),
                        "type" => "number",
                        "min" => 1,
                    );

                    /*if ($transactions_info->type == "payment_voucher" || $transactions_info->type == "receipt_voucher") {
                        $ref_array["data-rule-required"] = true;
                        $ref_array["data-msg-required"] = lang("field_required");
                    }*/ 

                    echo form_input($ref_array);
                ?>
                <!-- <?php if ($transactions_info->type == "payment_voucher" || $transactions_info->type == "receipt_voucher") {
                    echo '<a id="reference_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 1px; margin-top: -35px; font-size: 18px;"><span>×</span></a>';
                } ?> -->
            </div>
            

            <div class="col-md-2 input-group-btn">
                <button type="submit" class="btn btn-primary"><span class="fa fa-plus-circle"></span></button>
            </div>

            </div>
        </div>
        <?php echo form_close(); ?>
    <?php } ?>

    <div class="panel panel-default">
        
        <div id="valid_status">
            <?php $this->load->view("Transactions/valid_status"); ?>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="entries-table" class="display" cellspacing="0" width="100%">     
                </table>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#todo-inline-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                //$("#account").val("");
                //$("#type").val("");
                $("#amount").val("");
                //$("#narration").val("");
                if (typeof updateValidStatus == 'function') {
                    updateValidStatus('<?= $transactions_info->id ?>');
                }
                //$("#entries-table").appTable({newData: result.data, dataId: result.id});
                $("#entries-table").DataTable().ajax.reload();
                appAlert.success(result.message, {duration: 2000});
                $('#account').select2('open');
            }
        });

        $("#bank_cash").on("click", function () {
            saveBank('<?= $transactions_info->id ?>', $("#bank_cash").val());
        });
    });

    updateValidStatus = function (transactionId) {
        $.ajax({
            url: "<?php echo get_uri("transactions/update_valid_status"); ?>/" + transactionId,
            success: function (result) {
                if (result) {
                    $("#valid_status").html(result);
                }
            }
        });
    };

    saveBank = function (transactionId, accId) {
        $.ajax({
            url: "<?php echo get_uri("transactions/save_bank_account"); ?>/" + transactionId + "/" + accId,
            success: function (result) {
                console.log(result);
                if (result) {
                    if(result == "<?= lang('bank_account_is_updated') ?>") {
                        appAlert.success(result, {duration: 2000});
                    } else {
                        $('#bank_cash').select2('data', {id: 'accId', text: ''});
                        appAlert.error(result, {duration: 2000});
                    }
                }
            }
        });
    };
</script>


<script type="text/javascript">
    $(document).ready(function() {
    var show_options = false;
        if ("<?php echo $transactions_info->is_manual; ?>" == 1 || "<?php echo $this->login_user->is_admin; ?>") {
            show_options = true;
        }
    $("#entries-table").appTable({
        source: '<?php echo_uri("transactions/enteries_list_data/" . $transactions_info->id . "/") ?>',
        displayLength: 100,
        hideTools: false,
        stateSave: false,
        tableRefreshButton: true,
        columns: [ 
            {title: "<?= lang("id") ?>"}, 
            {title: "<?= lang("account_parent") ?>"},
            {title: "<?= lang("account") ?>"},
            {title: "<?= lang("notes") ?>"},
            {title: "<?= lang("DR") ?>" ,"class": "text-right w15p"},
            {title: "<?= lang("CR") ?>" ,"class": "text-right w15p"},
            {visible: show_options, searchable: show_options ,title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
        ],
        onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        printColumns: [0, 1, 2, 3, 4],
        xlsColumns: [0, 1, 2, 3, 4],
        summation: [{column: 4, dataType: 'number'}, {column: 5, dataType: 'number'}],

        onDeleteSuccess: function (result) {
                if (typeof updateValidStatus == 'function') {
                    updateValidStatus('<?= $transactions_info->id ?>');
                }
            },
        onUndoSuccess: function (result) {
                if (typeof updateValidStatus == 'function') {
                    updateValidStatus('<?= $transactions_info->id ?>');
                }
            },
        rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).addClass(aData[0]);
            }
        }); 

        $("#type").select2();
        //$("#branch_id").select2();
        $("#concerned_person").select2();
        $('#bank_cash').select2();
        //$("#units").select2();

        //show item suggestion dropdown when adding new item
        applySelect2OnItemTitle();

        $('#account').select2('open');

        //re-initialize item suggestion dropdown on request
        $("#account_parent_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        });

        //show item suggestion dropdown when adding new item
        /*if ('<?= $transactions_info->type ?>' == 'payment_voucher' || '<?= $transactions_info->type ?>' == 'receipt_voucher') {
            applySelect2OnReference();
        }
        
        $("#reference_dropdwon_icon").click(function () {
            applySelect2OnReference();
        });

        $("#unit").on("change", function () {
            console.log($('#unit').val());
            $('#reference').val('');
        });*/

    });


    updateValidStatus = function (transactionId) {
        $.ajax({
            url: "<?php echo get_uri("transactions/update_valid_status"); ?>/" + transactionId,
            success: function (result) {
                if (result) {
                    $("#valid_status").html(result);
                }
            }
        });
    };
 

    function applySelect2OnItemTitle() {
        $("#account").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("transactions/get_accounts_suggestion_enteries"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        });
    }

    /*function applySelect2OnReference() {
        $("#reference").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("transactions/get_references_suggestions"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        //q: term, // search term
                        unit: $('#units').val(),
                        t: '<?= $transactions_info->id ?>',
                        a: $('#account').val(),
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            console.log(e.val);
            if (e.val == "90985566") {
                //show simple textbox to input the new item
                console.log("!!sss");
                $("#reference").select2("destroy").val("").focus();
            }
        });
    }*/

</script>
