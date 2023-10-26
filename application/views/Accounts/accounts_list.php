<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('accounts_list'); ?></h1>
            <div class="title-button-group">
            </div>
        </div>
        <div class="table-responsive">
            <table id="assets-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#assets-table").appTable({
            source: '<?php echo_uri("accounts/accounts_list_data") ?>',
            columns: [
                {searchable: false, title: '<?php echo lang("id") ?>'},
                {title: '<?php echo lang("account_code") ?>'},
                {title: '<?php echo lang("account_name") ?>'},
                {title: '<?php echo lang("account_balance") ?>'},
                {searchable: false, title: '<?php echo lang("account_parent") ?>'},
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });


        $('#assets-table').on('click', '[data-action=view]', function(event){

           var  url = $(this).attr('data-action-url');
           var  id = $(this).attr('data-id');
           var  table = $(this);
           
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {id: id},
                success: function (result) {
                    table.closest('td').html(result.balance);
                }
            });

            
        });

    });

</script>