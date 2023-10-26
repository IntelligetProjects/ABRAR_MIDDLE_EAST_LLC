<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h4> <?php echo lang('treasury'); ?></h4>
            <div class="title-button-group">
                <a href="treasury" class="btn btn-default"><i class="fa  fa-refresh"></i><?php echo " " . lang('refresh'); ?></a>
                <?php echo modal_anchor(get_uri("treasury/treasury_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_treasury_account'), array("class" => "btn btn-default", "title" => lang('add_treasury_account'))); ?>
                <?php echo modal_anchor(get_uri("treasury/transfer_money"), "<i class='fa fa-angle-double-left'></i><i class='fa fa-angle-double-right'></i> " . lang("transfer_money"), array("class" => "btn btn-default", "title" => lang("transfer_money"))); ?>
                <!-- <?php echo modal_anchor(get_uri("treasury/default_treasury"), "<i class='fa fa-circle'></i> " . lang("default_treasury"), array("class" => "btn btn-default", "title" => lang("default_treasury"))); ?> -->
                <!-- <?php echo modal_anchor(get_uri("treasury/receive_money"), "<i class='fa fa-plus-circle'></i> " . lang("receive_money"), array("class" => "btn btn-default", "title" => lang("receive_money"))); ?>
                <?php echo modal_anchor(get_uri("treasury/spend_money"), "<i class='fa fa-minus-circle'></i> " . lang("spend_money"), array("class" => "btn btn-default", "title" => lang("spend_money"))); ?> -->
            </div>
        </div>
        <div class="table-responsive">
            <table id="treasury-table" class="table table-hoover" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#treasury-table").appTable({
            source: '<?php echo_uri("treasury/list_data") ?>',
            columns: [
                {title: '<?php echo lang("title") ?>'},
                {title: '<?php echo lang("account_number") ?>'},
                {title: '<?php echo lang("balance") ?>', "class": "text-center"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>