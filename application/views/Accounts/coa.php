<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang("chart_of_accounts") ?></h1>

            <div class="title-button-group">
                    <?php echo anchor(get_uri("accounts/accounts_list"), "<i class='fa fa-table'></i> ", array("class" => "btn btn-default", "title" => lang('table'))); ?>
                    <a href="accounts" class="btn btn-default"><i class="fa  fa-refresh"></i><?php echo " " . lang('refresh'); ?></a>
                    <?php echo modal_anchor(get_uri("accounts/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_account'), array("class" => "btn btn-default", "title" => lang('add_account'))); ?>

            </div>
        </div>
        <div class="panel-body">          
            <?php 
                $this->load->view("Accounts/tree_view"); 
            ?>
        </div>

    </div>
</div>

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script> -->


<script type="text/javascript">
    
    $(document).ready(function () {
        $('#accounts-view').on('click', '.viewBtn', function(e){                 
                var url = "<?php echo get_uri("accounts/view/")?>" + $(this).data('id');
                e.preventDefault();
                window.open(url);
        }); 

        $('#accounts-view').on('click', '.deleteBtn', function(){
                var urlDelete = "<?php echo get_uri("Accounts/delete/")?>" + $(this).data('id');
                if (confirm('Are you sure, you want to delete it?')) {
                    $.ajax({url: urlDelete, success: function(result){
                            location.reload();
                      }});
                }
        });

        $('#accounts-view').on('dblclick', '.jstree-node .jstree-anchor', function(){
            var url = "<?php echo get_uri("accounts/view/")?>" + $(this).parent().attr('id');
            window.open(url);
        });

    });    

</script>



