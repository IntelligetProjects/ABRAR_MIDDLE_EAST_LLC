<style>
.cen{
   width: 90px;
   border-radius: 5px;
}
</style>
<div id="page-content" class="p20 clearfix">

<div class="page-title clearfix">
            <h1> <?php echo lang("email_messages") ; ?></h1>
           
            <div class="title-button-group">
            <a href="<?php echo get_uri("email_messages/message") ?>"><button class="btn btn-primary"><?php echo lang('compose_email') ?></button></a>
            </div>
        </div>


    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="todo-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<?php $this->load->view("todo/helper_js"); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#todo-table").appTable({
            source: '<?php echo_uri("email_messages/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("action"); ?>',  "class": "w200"},
                {title: '<?php echo lang("status"); ?>',  "class": "w100"},
                {title: '<?php echo lang("date"); ?>', "iDataSort": 3, "class": "w200"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            
            printColumns: [2, 4],
            xlsColumns: [2, 4],
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).addClass(aData[0]);
            },
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>