<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang("payroll") . " :  " . lang(date_format(date_create($item_info->month),"F")).'-'.date_format(date_create($item_info->month),"Y"); ?></h1>
            <span class='label <?= $status ?> large'><?= lang($item_info->status) ?></span>
            <div class="title-button-group" id='approval'>
                <?php if ($item_info->status == 'draft') { 
                    echo js_anchor("<i class='fa fa-question'></i>". lang("request_approval"), array("class" => "btn btn-warning", "data-id" => $item_info->id, "data-action-url" => get_uri("payroll/status"), "data-status" => 'pending',"data-action" => "approval"));                
                }
                if ($item_info->status == 'pending' && $can_approve) {
                    echo js_anchor("<i class='fa fa-check'></i>". lang("approve"), array("class" => "btn btn-success", "data-id" => $item_info->id, "data-action-url" => get_uri("payroll/status"), "data-status" => 'approved', "data-action" => "approval"));
                } 

                if ($item_info->status == 'approved' && $can_approve) {
                    ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                    <i class='fa fa-money'></i>
                    <?php echo lang("process") ?>
                    </button>

              <?php 
                    //echo js_anchor("<i class='fa fa-money'></i>". lang("process"), array("class" => "btn btn-primary", "data-id" => $item_info->id, "data-action-url" => get_uri("payroll/status"), "data-status" => 'processed', "data-action" => "approval"));
                }
                ?>

            </div>
            
        </div>
        <!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Select Bank / Treasury</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="post" id="set_bank" action="<?php echo get_uri("payroll/status") ?>">
        <input type="hidden" name="id" value="<?php echo  $item_info->id ?>">
        <input type="hidden" name="value" value="processed">
      <div class="form-group" id="bank" >
            <label for="bank" class=" col-md-3"><?php echo lang('Bank / Treasury'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("bank", $banks_dropdown,'', "required class='select2 form-control validate-hidden' id='bank_name' data-rule-required='true' data-msg-required='" . lang('field_required') . "' ");
                ?>
            </div>
        </div>
        <br>   
        <button type="submit" id="" class="btn btn-primary" style="margin:auto;display:block;margin-top:30px">Process</button>
      </form>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>

        <div class="table-responsive">
            <table id="payroll-detail-table" class="table table-hoover" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#sidebar').addClass("collapsed");
        $("#payroll-detail-table").appTable({
            source: '<?php echo_uri("payroll/detail_list_data/" . $payroll_id) ?>',
            order: [[0, 'asc']],
            columns: [
                {title: "<?php echo lang("Employee") ?>"},
                {title: "<?php echo lang("Bank") ?>"},
                {title: "<?php echo lang("Acc Title") ?>"},
                {title: "<?php echo lang("Acc No") ?>"},
                {title: "<?php echo lang("Salary") ?>"},
                {title: "<?php echo lang("Bonus") ?>"},
                {title: "<?php echo lang("Bonus Reason") ?>", bVisible: false},
                {title: "<?php echo lang("Deductions") ?>"},
                {title: "<?php echo lang("Deductions Reason") ?>", bVisible: false},
                {title: "<?php echo lang("Advance") ?>"},
                {title: "<?php echo lang("Loan") ?>"},
                {title: '<?php echo lang("PASI_share") ?>'},
                {title: '<?php echo lang("job_security_share") ?>'},
                {title: '<?php echo lang("unpaid_leaves") ?>'},
                {title: '<?php echo lang("leaves_deduction") ?>'},
                {title: "<?php echo lang("Payable Salary") ?>"},
                <?php if($this->db->dbprefix=='aitech'|| $this->db->dbprefix=='V3'){?>
                {title: "<?php echo lang("Attachment") ?>"},
                <?php } ?>
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            summation: [{column: 4, dataType: 'number'},{column: 15, dataType: 'number'}],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3]
        });

        $('[data-toggle="tooltip"]').tooltip();

         
        $('#approval').on('click', '[data-action=approval]', function(){                
            var url = $(this).attr('data-action-url'),
                id = $(this).attr('data-id'),
                status = $(this).attr('data-status');
                
            var $table = $(this);
            appLoader.show({css: "bottom: 40%; right: 40%;"})
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {id: id, value: status},
                success: function (result) {
                    window.location.reload(); 
                    appLoader.hide();
                }
            });
        }); 
    
        $("#set_bank").appForm({
            onSuccess: function (result) {
                    location.reload();
            }
        });

       

    });
</script>