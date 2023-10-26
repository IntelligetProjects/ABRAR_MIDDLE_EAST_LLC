<style>
   tr.selected {background-color: #a6a6a6;}
   .select2-drop {
  width: 350px !important;
    }
 </style>
<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1><?php echo lang('account_statment') . $account_name . $account_description; ?></h1>
            <div id="account_parent_dropdwon_icon" class="form-group">
                <div class="title-button-group" style=" margin-top: 5px;">
                    <?php
                        echo form_input(array(
                            "id" => "account",
                            "name" => "account",
                            "value" => '',
                            "class" => "form-control validate-hidden",
                        ));
                    ?>
                    <a  tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px; margin-right: 130px;"><span style ="float: right !important;"><?php echo lang("account")?></span></a>
                </div>
            </div>          
        </div>
        <div class="table-responsive">
            <table id="transactions_table" class="display" cellspacing="0" width="100%">        </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
         
         $("#transactions_table").appTable({
            source: '<?php echo_uri("accounts/get_entries/$account") ?>',
            tableRefreshButton: true,
            stateSave : false,
            rangeDatepicker: [{startDate: {name: "start_date", value: "<?= $start_date ?>"}, endDate: {name: "end_date", value: "<?= $end_date ?>"}, showClearButton: true}],
            filterDropdown: [
                
                {name: "concerned_person", class: "w200", options: <?php echo $concerned_persons_dropdown; ?>},
                
            ],
            order: [[0, "asc"]],
            displayLength: 100,
            
            columns: [
                {title: "<?php echo lang('date') ?>","class": "w10p"},
                {title: "<?php echo lang('account_code') ?>"},
                {title: "<?php echo lang('account') ?>"},
                {title: "<?php echo lang('narration') ?>"},
                
                {title: "<?php echo lang('concerned_person') ?>"},
                {title: "<?php echo lang('reference') ?>","class": "text-center"},
                {title: "<?php echo lang('DR') ?>","class": "text-center"},
                {title: "<?php echo lang('CR') ?>","class": "text-center"},
                {title: "<?php echo lang('balance') ?>","class": "text-center"}
            ],
            summation: [{column: 6, dataType: 'number'}, {column: 7, dataType: 'number'}],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns:[0, 1, 2, 3, 4, 5],
            onInitComplete: function () {
                if(window.outerWidth < 767) { 
                    $('table').stacktable();
                    
                }
            },
        });

        $('#transactions_table').on('click', 'tr', function (e) {
            $('#transactions_table tr td').css("background-color", "transparent");
            if($(this).css('background-color') == "rgb(255, 255, 0)")
            {
                $(this).css("background-color", "white");
            } else {
                $(this).css("background-color", "yellow");
            }
            
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $account_name; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#account_parent_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        })

    });

    function applySelect2OnItemTitle() {
        $("#account").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("accounts/get_accounts_suggestion_ledger"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val) {
                window.location = "<?php echo site_url('accounts/view'); ?>/" + e.val;
            }

        });
    }

</script>