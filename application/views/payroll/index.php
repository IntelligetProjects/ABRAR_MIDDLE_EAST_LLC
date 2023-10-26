<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang("payroll") ; ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("payroll/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('generate_payroll'), array("class" => "btn btn-default", "title" => lang('generate_payroll'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="payroll-table" class="table table-hoover" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<?php 
$status = array(array("id"=>"draft", "text"=>"draft"), array("id"=>"pending", "text"=>"pending"), array("id"=>"approved", "text"=>"approved"), array("id"=>"processed", "text"=>"processed"));
?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payroll-table").appTable({
            source: '<?php echo_uri("payroll/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: '<?= lang("month") ?>', "class": "w20p"},
                {title: '<?= lang("status") ?>'},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
                
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
            printColumns: [0, 1, 2],
            xlsColumns: [0, 1, 2]
        });

    $('body').on('click', '[data-act=update-task-status]', function () {
            $(this).editable({
                type: "select2",
                pk: 1,
                name: 'status',
                ajaxOptions: {
                    type: 'post',
                    dataType: 'json'
                },
                value: $(this).attr('data-value'),
                url: '<?php echo_uri("payroll/status") ?>/0' + '/' + $(this).attr('data-id'),
                showbuttons: false,
                source: <?php echo json_encode($status) ?>,
                success: function (response, newValue) {
                    if (response.success) {
                        $("#payroll-table").DataTable().ajax.reload();
                    }
                }
            });
            $(this).editable("show");
        });


    });
</script>