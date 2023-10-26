<div class="panel">
    <div class="tab-title clearfix">
        <h4><?php echo lang('files'). " - ". $eroom_id; ?></h4>
        <div class="title-button-group">
            <?php
            echo modal_anchor(get_uri("eroom/file_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_files'), array("class" => "btn btn-default", "title" => lang('add_files'), "data-post-eroom_id" => $eroom_id));
            ?>
        </div>
    </div>

    <?php  $table_id = "file_table".$eroom_id;
    $table_hash = "#file_table".$eroom_id;
     ?>

    <div class="table-responsive">
        <table id="<?= $table_id ?>" class="display" width="100%">            
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {


        $("<?= $table_hash ?>").appTable({
            source: '<?php echo_uri("eroom/files_list_data/" . $eroom_id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: '<?php echo lang("id") ?>'},
                {title: '<?php echo lang("file") ?>'},
                {title: '<?php echo lang("size") ?>'},
                {title: '<?php echo lang("uploaded_by") ?>'},
                {title: '<?php echo lang("created_date") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });
    });
</script>