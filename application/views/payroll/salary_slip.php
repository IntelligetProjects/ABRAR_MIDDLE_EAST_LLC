<style>
    form{
    display: inline-block;
    border: 1px dashed gray;
    width: 200px;
    }
    .btn-primary{
        padding: 5px 15px;
    }
    </style>
<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
        <h1> <?php echo "Salary Slip" ; ?></h1>
        <?php if($this->db->dbprefix=='aitech'|| $this->db->dbprefix=='V3'){?>
        <?php echo form_open(get_uri("payroll/upload_files/$salary_details->employee_id/$salary_details->payroll_id"), array("id" => "invoice-form", "class" => "general-form", "role" => "form")); ?>
               
        <div id="events-dropzone" class="post-dropzone">
            <?php $this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 
            <?php $this->load->view("includes/dropzone_preview"); ?>
                <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class="fa fa-camera"></i> <?php echo lang("attach_file"); ?></button>
                <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>

            </div>
        <?php echo form_close(); ?>
        <?php } ?>
        <div class="title-button-group">
            <button class="btn btn-success no-print" id="print_me">PRINT</button>
        </div>
        </div>
    </div>
</div>
<div class="panel-body">
    <table class="table table_header">
        <tr>
            <td><b>Employee Name</b></td>
            <td><?= $salary_details->employee?></td>
            <td><b>Leave Taken</b></td>
            <td><?= $total_leaves ?></td>
        </tr>
        <tr>
            <td><b>Employee Designation</b></td>
            <td><?= ucwords($salary_details->job_title)?></td>
            <td><b>Leave without Pay</b></td>
            <td><?= $total_unpaid_leaves ?></td>
        </tr>
        <tr>
            <td><b>Month</b></td>
            <td><?= $month ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><b>Year</b></td>
            <td><?= $year?></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <br>
    <table class="table table_body">
        <tr>
            <td colspan="4" style="text-align: center;" bgcolor="#dfdfdf"><b>DETAILS</b></td>
        </tr>
        <tr>
            <td><b>Earnings</b></td>
            <td><b>Amount (OMR)</b></td>
            <td><b>Deductions</b></td>
            <td><b>Amount (OMR)</b></td>
        </tr>
        <tr>
            <td><b>Gross Salary</b></td>
            <td><?= bcdiv($salary_details->salary,1,3)?></td>
            <td><b>Loan</b></td>
            <td><?= bcdiv($salary_details->loan,1,3)?></td>
        </tr>
        <tr>
            <td><b>Bonus</b></td>
            <td><?= $salary_details->manual_bounce?></td>
            <td><b>Advance</b></td>
            <td><?= bcdiv($salary_details->advance,1,3)?></td>
        </tr>
        <tr>
            <td><b>Bonus Reason</b></td>
            <td><?= $salary_details->manual_bonus_reason?></td>
            <td><b>PASI</b></td>
            <td><?= bcdiv($salary_details->pasi_employee,1,3)?></td>
        </tr>
        <tr>
            <td><b></b></td>
            <td></td>
            <td><b>Job Security</b></td>
            <td><?= bcdiv($salary_details->job_s_employee,1,3)?></td>
        </tr>
        <tr>
            <td><b></b></td>
            <td></td>
            <td><b>Leave Deduction</b></td>
            <td><?= bcdiv($leave_deduction,1,3) ?></td>
        </tr>
        <tr>
            <td><b></b></td>
            <td></td>
            <td><b>Other Deductions</b></td>
            <td><?= $salary_details->manual_deduction?></td>
        </tr>
        <tr>
            <td><b></b></td>
            <td></td>
            <td><b>Other Deduction Reasons</b></td>
            <td><?= $salary_details->manual_deduction_reason?></td>
        </tr>
        <?php
            $total_earnings = $salary_details->salary +  $salary_details->manual_bounce;
            $total_deductions = $salary_details->loan+ $salary_details->advance + $salary_details->pasi_employee +$salary_details->job_s_employee + $leave_deduction - $salary_details->manual_deduction;
            $payable_salary = $total_earnings - $total_deductions;
        ?>
        <tr>
            <td><b>Total Earnings</b></td>
            <td><b><?= bcdiv($total_earnings,1,3)?> /- OMR</b></td>
            <td><b>Total Deductions</b></td>
            <td><b><?= bcdiv($total_deductions,1,3)?> /- OMR</b></td>
        </tr>
        <tr>
            <td><b>Net Salary</b></td>
            <td><b><?= bcdiv($payable_salary,1,3)?> /- OMR</b></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4"><b>Credited To: </b><?= $salary_details->bank_title?> A/C # <?= $salary_details->account_no?></td>
        </tr>
    </table>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
   // file upload 
        var uploadUrl = "<?php echo get_uri("invoices/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("invoices/validate_events_file"); ?>";
        var dropzone = attachDropzoneWithForm("#events-dropzone", uploadUrl, validationUri);
        $('#print_me').click(function(){
            window.print();
        });
    });
</script>