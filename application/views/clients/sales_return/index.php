<div id="page-content" class="clearfix p20">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('sale_returns'); ?></h1> 
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    
                    <?php
                    if($can_create){
                    echo modal_anchor(get_uri("sale_returns/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_sale_return'), array("class" => "btn btn-default mb0", "title" => lang('add_sale_return')));
                    }
                    ?>
                </div>
            </div>           
        </div>
        
        <div class="table-responsive">
            <table id="delivery-table" class="table table-hoover" cellspacing="0" width="100%">   
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#delivery-table").appTable({
            source: '<?php echo_uri("sale_returns/list_client_data/$client_id ")?>',
            order: [[0, "desc"]],
            //dateRangeType: "monthly",
            columns: [
                {title: "<?php echo lang("id") ?> "},
                {title: "<?php echo lang("sale_return") ?> ", "class": "w15p"},
                {title: "<?php echo lang("invoice") ?> "},
                {title: "<?php echo lang("client") ?> "},
                {title: "<?php echo lang("sale_return_date") ?>", "iDataSort": 2, "class": "w100"},
                {title: "<?php echo lang("total_value") ?> "},
                {title: "<?php echo lang("tax_value") ?> "},
                {title: "<?php echo lang("payment_method") ?> "},
                {title: "<?php echo lang("note") ?> "},
                {title: "<?php echo lang("status") ?> "},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1,2],
            xlsColumns: [0, 1,2],
            summation: [{column: 5, dataType: 'number'}, {column: 6, dataType: 'number'}]
            
        });
    });
</script>