<?php echo form_open(get_uri("email_messages/save"), array("id" => "todo-form", "class" => "general-form", "role" => "form")); ?>
<?php //var_dump($to_data); die() ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
                <label for="to" class=" col-md-3"><?php echo lang('send_to'); ?></label>
                <div class="col-md-12" id="dropdown-apploader-section">
                    <?php
                    echo form_input(array(
                        "id" => "to",
                        "name" => "to",
                        "value" => $model_info->to,
                        "class" => "form-control",
                        "data-rule-required" => true,   
                        "placeholder" => lang('send_to')
                    ));
                    ?>
                </div>
            </div>
    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control notepad-title",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="notepad">
                <?php
                // echo form_textarea(array(
                //     "id" => "message",
                //     "name" => "message",
                //     "value" => $model_info->message,
                //     "class" => "form-control",
                //     "placeholder" => lang('message') . "...",
                //     "data-rich-text-editor" => true
                // ));
                ?>
            </div>
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
            <!-- <div id="summernote"><p>Hello Summernote</p></div> -->
            <textarea id="summernote" name="message" >
            <?php  echo $model_info->message ?>
            </textarea> 
                <script>
                   $(document).ready(function() {
                        $('#summernote').summernote({
                            placeholder: '<?php echo lang('your_message') ?>',
                            height: 200,
                            toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview']]
                            ]
                        });
                        
                    });
                </script>
           </div>
    </div>
   

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#todo-form").appForm({
            onSuccess: function (result) {
                $("#todo-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
 
        $("#to").select2({multiple: true, data: <?php echo json_encode($to_data); ?>});
    });
</script>    

<script>
    //   $(document).ready(function () {
    //   $('#collaborators').select2("destroy");
    //   $("#collaborators").hide();
     
       
    //   });
</script>