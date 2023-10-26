
<table id="vat-summery-table" class="display" cellspacing="0" width="100%">   
            </table>

           

               
<style>
    img{
    width: 178px;
    margin: auto;
    display: block;
    border: 2px solid #81b3d2;
    margin-bottom: 20px;
    border-radius: 15px;
    }
    form{
    width: 75%;
    margin: auto;
    }
    .modal-title{
        text-align: center;
    display: block;
    }
    .pay{
        padding: 10px;
    margin-top: 15px;
    border: 8px solid #94cd94;
    color: #fff;
    width: 270px;
    height: 66px;
    font-size: 14px;
    font-weight: bold;
    margin: auto;
    display: block;
    margin-bottom: 30px;
    text-transform: uppercase;
    }
    .pay i{
        font-size: 20px;
    margin-top: 5px;
    margin-right: 15px;
    margin-left: 10px;
    }
</style>

                <button class="btn btn-success pay" data-toggle="modal" data-target="#login"><i class="fa fa-money"></i>Proceed to payment</button>
<!-- Modal -->
<div id="login" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Login</h4>
      </div>
      <div class="modal-body">
        <img src="/tarteeb_v3/assets/images/dl.jpg" class="img-fluid">
       <form >
        <div class="form-group">
            <label>User Name:</label>
            <input type="text" name="user_name" class="form-control">
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password"  class="form-control">
        </div>
        
       </form>
      </div>
      <div class="modal-footer">
      <a href="<?php echo_uri("thawani/get_payment_url"); ?>">
        <button style="margin: auto;
    display: block;
    width: 200px;" type="button" class="btn btn-success">Login</button>
      </a>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
     loadPurchaseImportTable = function (selector) {
    var customDatePicker = "";

    $(selector).appTable({
    source: '<?php echo_uri("vat_report/list_data_vat_summery") ?>',
            dateRangeType: 'yearly',
            columnShowHideOption:false,
            // order: [[0, "desc"]],
            filterDropdown: [
                {name: "quarter", class: "w150", options: <?php $this->load->view("vat-report/year_quarter_dropdown"); ?>},
            ],
            rangeDatepicker: customDatePicker,
            columns: [
            {title: "<?php echo lang("VAT_summery") ?>", "class": ""},
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
    });
    };
    $(document).ready(function () {
        loadPurchaseImportTable("#vat-summery-table");
    });
</script>