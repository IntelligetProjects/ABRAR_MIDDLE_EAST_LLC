<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('goods'); ?></h1>
            <?php if ($can_create_module) { ?>
            <div class="title-button-group">
                <!-- <?php echo modal_anchor(get_uri("items/import_items_modal_form"), "<i class='fa fa-upload'></i> " . lang('import_goods'), array("class" => "btn btn-default", "title" => lang('import_goods'))); ?> -->
                <?php echo modal_anchor(get_uri("items/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_good'), array("class" => "btn btn-default", "title" => lang('add_good'))); ?>
            </div>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table id="item-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-table").appTable({
            source: '<?php echo_uri("items/list_data") ?>',
            order: [[0, 'desc']],
            filterDropdown: [
                {name: "category_id", class: "w200", options: <?php echo $categories_dropdown; ?>},
                {name: "item_type", class: "w200", options: <?php echo $types_dropdown; ?>},
                {name: "id", class: "w200", options:[{"id":1,"text":"1"},{"id":2,"text":"2"}] },
            ],
            columns: [
                {title: "<?php echo lang('id') ?>"},
                {title: "<?php echo lang('title') ?> ", "class": "w20p"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('type') ?>"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('unit_type') ?>", "class": "w100"},
                <?php if( $this->db->dbprefix=='Tadqeeq'){ ?>
                {title: "<?php echo lang("certificate_no") ?>", "class": "w10p text-right"},
                <?php } ?>
                {title: "<?php echo lang('cost_price') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('rate') ?>", "class": "text-right w100"},
                {title: "<?php echo lang('purchased') ?>"},
                {title: "<?php echo lang('received') ?>"},
                {title: "<?php echo lang('sold') ?>"},
                {title: "<?php echo lang('delivered') ?>"},
                {title: "<?php echo lang('stock') ?>"},
                <?php if( $this->db->dbprefix=='tarteeb_v3'){ ?>
                {title: "<?php echo lang('max_stock') ?>"},
                <?php } ?>
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
                
            ],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                }
            },
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>