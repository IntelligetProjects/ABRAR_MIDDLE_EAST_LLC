<style>
    .editable_inputs
    {
        background: none;
        outline: none;
        border: none;

        border: 1px solid rgba(81, 203, 238, 1);
        box-shadow: 0 0 5px rgba(81, 203, 238, 1);
    }
    @media print {
    
        .editable_inputs 
        {
            border: 0px solid rgba(81, 203, 238, 1);
            box-shadow: 0 0 0px rgba(81, 203, 238, 1);
        }

      }
      body{
          zoom: 1.0!important;
      }
</style>
<?php 
    $national_fields_class = "";
    if($employee_info->national == 0)
    {
        $national_fields_class = "hidden";
    }
?>
<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang("final_settlement") ; ?></h1>
            <div class="title-button-group">
                <button class="btn btn-success no-print" id="print_me"><?php echo lang("print") ; ?></button>
            </div>
        </div>
    </div>
</div>
<div class="panel-body" style="overflow: scroll;margin-bottom: 20px;" id="print-area">

    <table class="table table_header">
        <tr>
            <td><b><?php echo lang("date")?></b></td>
            <td><?= $current_date?></td>
        </tr>
        <tr>
            <td><b><?php echo lang("employee_name")?></b></td>
            <td><?= $employee_name?></td>
        </tr>

        <tr>
            <td><b><?= lang('designation');?></b></td>
            <td><?= $designation?></td>
        </tr>

        <tr>
            <td><b><?= lang('date_of_joining');?></b></td>
            <td><?= $date_of_joining?></td>
        </tr>

        <tr>
            <td><b><?= lang('last_date_work');?></b></td>
            <td><?= $last_date_work?></td>
        </tr>

        <tr>
            <td><b><?= lang('years_of_employment');?></b></td>
            <td><?= $years_of_employment?></td>
        </tr>

        <tr>
            <td><b><?= lang('reason');?></b></td>
            <td><?= $reason?></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;"><b><?= lang('salary_details_per_month');?> (OMR)</b></td>
        </tr>

        <tr>
            <td><?= lang('salary');?></td>
            <td><?= $salary?></td>
        </tr>

        <tr>
            <td><?= lang('housing');?></td>
            <td><?= $housing?></td>
        </tr>

        <tr>
            <td><?= lang('transportation');?></td>
            <td><?= $transportation?></td>
        </tr>

        <tr>
            <td><?= lang('telephone');?></td>
            <td><?= $telephone?></td>
        </tr>

        <tr>
            <td><?= lang('utility');?></td>
            <td><?= $utility?></td>
        </tr>

        <tr>
            <td><?= lang('gross_salary');?></td>
            <td><?= $gross_salary?></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;"><b><?= lang('payables');?> (OMR)</b></td>
        </tr>

        <tr>
            <td><?= lang('last_month_salary');?></td>
            <td><?= $last_month_salary?></td>
        </tr>

        <tr>
            <td><?= lang('gratuity');?></td>
            <td><input class="editable_inputs" type="text" value="<?= $gratuity?>" id="gratuity"></td>
        </tr>

        <tr>
            <td><?= lang('leaves_reimbursement');?></td>
            <td><input class="editable_inputs" type="text" value="0" id="leave_reimbursement"></td>
        </tr>

        <tr>
            <td><?= lang('other_payable');?></td>
            <td><input class="editable_inputs" type="text" value="0" id="other_payable"></td>
        </tr>

        <tr>
            <td><?= lang('other_payable_reason');?></td>
            <td><input class="editable_inputs" type="text" value=""></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;"><b><?= lang('deductions');?> (OMR)</b></td>
        </tr>

        <tr class="<?= $national_fields_class?>">
            <td><?= lang('PASI');?></td>
            <td><input class="editable_inputs" type="text" value="<?= $last_month_pasi?>" id="pasi"></td>
        </tr>

        <tr class="<?= $national_fields_class?>">
            <td><?= lang('job_security');?></td>
            <td><input class="editable_inputs" type="text" value="<?= $last_month_job_s?>" id="job_s"></td>
        </tr>

        <tr>
            <td><?= lang('unpaid_leaves').' ( '.$unpaid_leaves.' ) day/days';?></td>
            <td><input class="editable_inputs" type="text" value="<?= bcdiv($unpaid_leaves_deductions,1,3)?>" id="leaves_deductions"></td>
        </tr>

        <tr>
            <td><?= lang('loan');?></td>
            <td><input class="editable_inputs" type="text" value="<?= bcdiv($loan_amount,1,3)?>" id="loan"></td>
        </tr>

        <tr>
            <td><?= lang('advance');?></td>
            <td><input class="editable_inputs" type="text" value="<?= bcdiv($advance_amount,1,3)?>" id="advance"></td>
        </tr>

        <tr>
            <td><?= lang('other_deductable');?></td>
            <td><input class="editable_inputs" type="text" value="0" id="other_deductable"></td>
        </tr>

        <tr>
            <td><?= lang('other_deductable_reason');?></td>
            <td><input class="editable_inputs" type="text" value=""></td>
        </tr>

        <tr>
            <td><b><?= lang('net_payable');?></b></td>
            <td id="net_payable"></td>
        </tr>
    </table><br><hr>

    <h5><?php echo lang("prepared_by")?>: HR&A</h5><br>
    <h5><?php echo lang("checked_by")?>: <?php echo lang("accounts")?></h5><br>
    <h5><?php echo lang("approved_by")?>:</h5><br>



    <h5><?php echo lang("employee_signature_and_date")?>:</h5>
</div>
</div>

<script type="text/javascript">

        function myPrint(){
            var mystyle = document.getElementsByTagName("head")[0];
        var mywindow = window.open('', 'new div', 'height=400,width=600');
      mywindow.document.write('<html>');
      mywindow.document.write(mystyle.innerHTML);
      mywindow.document.write('<body>');
      mywindow.document.write( document.querySelector(".panel-body").innerHTML);
      mywindow.document.write('</body></html>');
      mywindow.document.close();
      mywindow.focus();
      setTimeout(function(){mywindow.print();},1000);
      return true;
        }

    $(document).ready(function () {

        $('#print_me').click(function(){
            myPrint();
        });
       

        cal_total();

        function cal_total()
        {
            var last_month_salary = parseFloat("<?= $last_month_salary?>");
            var gratuity = parseFloat($('#gratuity').val());
            var leave_reimbursement = parseFloat($('#leave_reimbursement').val());
            var other_payable = parseFloat($('#other_payable').val());

            var pasi = parseFloat($('#pasi').val());
            var job_s = parseFloat($('#job_s').val());
            var leaves_deductions = parseFloat($('#leaves_deductions').val());
            var loan = parseFloat($('#loan').val());
            var advance = parseFloat($('#advance').val());
            var other_deductable = parseFloat($('#other_deductable').val());

            var payables = last_month_salary + gratuity + leave_reimbursement + other_payable;
            var deductables = pasi + job_s + leaves_deductions + loan + advance + other_deductable;

            var net_payable = (payables - deductables).toFixed(3);

            $('#net_payable').html(net_payable + ' /- OMR');
        }


        $('.editable_inputs').on('change , keyup',function(){
            cal_total();
        });

    });
</script>