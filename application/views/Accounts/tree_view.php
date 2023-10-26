<div class=" col-md-3">
    <?php 
        echo form_input(array(
            "id" => "searchMe",
            "value" => '',
            "class" => "form-control",
            "placeholder" => lang("search_account"),
            "autocomplete" => 'off',
        ));
    ?>
</div>

<div style='clear: both; margin: 30px;'>
    <div id="accounts-view" class="display" cellspacing="0" width="100%"></div>
</div>


<script>
    $(document).ready(function () {
        $('#accounts-view').jstree({
            "plugins": ["themes", "json_data", "dnd", "wholerow", "search"],
                'core': {
                'data': {
                    'url': function (node) {
                        if(node.id === '#') {
                            return "<?php echo get_uri('Accounts/get_accounts/') ?>";
                        }
                        else {
                            return "<?php echo get_uri('Accounts/get_accounts/') ?>" + node.id;
                        }
                    },
                    'dataType': "json"
                },
                "themes" : {
                      "variant" : "large",
                      "dots" : true,
                      "icons" : true
                    }
            }
        });

        var to = false;
          $('#searchMe').keyup(function () {
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
              var v = $('#searchMe').val();
              $('#accounts-view').jstree(true).search(v);
            }, 250);
        });

            $('#accounts-view').on('dblclick', '.jstree-node .jstree-anchor', function(){

            $("#ajaxModal").modal('hide');
            var url = "<?php echo get_uri("Accounts/view/")?>" + $(this).parent().attr('id');
            window.open(url);
        });

    });
</script>