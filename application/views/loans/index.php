<div id="page-content" class="p20 clearfix">
    <div class="row">

        <div class="col-sm-12 col-lg-12">
            <div class="panel panel-default">

                <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                    <li><a  role="presentation" class="active" href="javascript:;" data-target="#task-status-tab"> <?php echo lang('List of Loans'); ?></a></li>
                    <div class="tab-title clearfix no-border">
                        <div class="title-button-group">
                            <?php echo modal_anchor(get_uri("loans/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_loan'), array("class" => "btn btn-default", "title" => lang('add_loan'))); ?>
                        </div>
                    </div>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade" id="task-status-tab">
                        <div class="table-responsive">
                            <table id="loans-table" class="display" cellspacing="0" width="100%">         
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#loans-table").appTable({
            rangeDatepicker: [{startDate: {name: "start_date", value: ''}, endDate: {name: "end_date", value: ''}, showClearButton: true}],
            source: '<?php echo_uri("loans/list_data") ?>',
            order: [[0, "asc"]],
            displayLength: 100,
            columns: [
                {title: '<?php echo lang("id"); ?>'},
                {title: '<?php echo lang("employee"); ?>'},
                {title: '<?php echo lang("amount"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]

        });
    });
</script>