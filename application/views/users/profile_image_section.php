<style>
   #file_upload input[type="file"] {
    display: none;
}

.custom-file-upload {
    /* display: block!important; */
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
    width:inherit;
    text-align: center;
    background-color:#0a0a23;
    color: #fff;
    border-radius:10px;
    box-shadow: 0px 0px 2px 2px rgb(0,0,0);
}
.btn-info{
    width: -webkit-fill-available;
}
</style>
<div class="box" id="profile-image-section">
    <div class="box-content w200 text-center profile-image">
        <?php
        $url = "team_members";

        //set url
        if ($user_info->user_type === "client") {
            $url = "clients";
        }
        echo form_open(get_uri($url . "/save_profile_image/" . $user_info->id), array("id" => "profile-image-form", "class" => "general-form", "role" => "form"));
        ?>
        <?php if ($this->login_user->is_admin || $user_info->id === $this->login_user->id) { ?>
            <div class="file-upload btn mt0 p0" style="vertical-align: top;  margin-left: -45px; ">
                <span><i class="btn fa fa-camera" ></i></span> 
                <input id="profile_image_file" class="upload" name="profile_image_file" type="file" data-height="200" data-width="200" data-preview-container="#profile-image-preview" data-input-field="#profile_image" />
            </div>
            <input type="hidden" id="profile_image" name="profile_image" value=""  />
        <?php } ?>
        <span class="avatar avatar-lg"><img id="profile-image-preview" src="<?php echo get_avatar($user_info->image); ?>" alt="..."></span> 
        <h4 class=""><?php echo $user_info->first_name . " " . $user_info->last_name; ?></h4>
        <?php echo form_close(); ?>
    </div> 


    <div class="box-content pl15">
        <p class="p10 m0"><label class="label label-info large"><strong> <?php echo $user_info->job_title; ?> </strong></label></p> 

        <?php if ($show_cotact_info) { ?>
            <p class="p10 m0"><i class="fa fa-envelope-o"></i> <?php echo $user_info->email ? $user_info->email : "-"; ?></p> 
            <?php if ($user_info->phone || $user_info->skype) { ?>
                <p class="p10 m0">
                    <?php if ($user_info->phone) { ?>
                        <i class="fa fa-phone"></i> <?php echo $user_info->phone; ?> <span class="mr15"></span>
                        <?php
                    }
                    if ($user_info->skype) {
                        ?>
                        <i class="fa fa-skype"></i> <?php echo $user_info->skype; ?>
                    <?php } ?>
                </p>
            <?php } ?>
        <?php } ?> 

        <div class="p10 m0 clearfix">
            <div class="pull-left">
                <?php
                if ($show_social_links) {
                    social_links_widget($social_link);
                }
                ?>
            </div>
            <?php
            if ($user_info->id != $this->login_user->id) {

                $show_message_button = true;

                //don't show message button in client contact's page if user hasn't permission to send/receive message to/from client
                if ($user_info->user_type === "client") {
                    $client_message_users = get_setting("client_message_users");
                    $client_message_users_array = explode(",", $client_message_users);
                    if (!in_array($this->login_user->id, $client_message_users_array)) {
                        $show_message_button = false;
                    }
                } else if ($user_info->user_type === "lead") {
                    //don't show message button for lead contacts
                    $show_message_button = false;
                }

                if (get_setting("module_message") && $show_message_button) {
                    echo modal_anchor(get_uri("messages/modal_form/" . $user_info->id), "<i class='fa fa-envelope-o'></i> " . lang('send_message'), array("class" => "btn btn-transparent success btn-sm", "title" => lang('send_message')));
                }
            }
            ?>
        </div>
        <?php
         $url = base_url();
         $urlParts = explode('/', str_ireplace(array('http://', 'https://'), '', $url));
         $folder_domain= $urlParts[1];
         ?>
<div class="row">
    <div class="col-md-9">
        <?php if ($this->login_user->is_admin && $user_info->id === $this->login_user->id) {?>
        <form style="border: 2px dashed white;  width: 210px; padding: 10px;"method="post" id="file_upload" action="https://system.teamway.om/tasgeelxxx/index.php/upload" enctype="multipart/form-data">
                <alert style=" color: #0a588e;
                display: block;
                padding-bottom: 7px;"><?php echo lang('upload_your_CR_file') ?> </alert> 
                <label class ="custom-file-upload" for="fileInput"><?php echo lang('select_file')?></label>
            <input id="fileInput" type="file"  name="file"  required/>
                <input type="hidden" name="folder_domain" value="<?php echo  $folder_domain ?>">
                <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
                <input type="submit" name="upload" value="<?php echo lang('upload')?>"  class="btn btn-info" style="margin-top: 10px;"/>
            </form>
            <?php } ?>
    </div>
    <div class="col-md-3">
            <a style="display:none" href="" target="_blank" class="btn btn-info" id="show_file"><?php echo lang('show_file')?></a>
    </div>
</div>


    </div>
</div>


<script>
    $(document).ready(function () {
        //modify design for mobile devices
        if (isMobile()) {
            $("#profile-image-section").children("div").each(function () {
                $(this).addClass("p0");
                $(this).removeClass("box-content");
            });
        }

        $("#file_upload").appForm({
            isModal: false,
            onSubmit: function () {
                if($('#fileInput').val()==''){
                    appLoader.hide();
                     appAlert.error("Please Choose file", {duration: 10000});
                }
                appLoader.show();
            },
            onSuccess: function (result) {
                appLoader.hide();
                appAlert.success(result.message, {duration: 10000});
                setTimeout(
                function()   
                {
                    location.reload();
                }, 2000);
               
            },
            onError: function (result) {
                appLoader.hide();
                appAlert.error(result.message, {duration: 10000});
            }
        });

    });
</script>

<script type="text/javascript">
    // $(document).ready(function () {
    //     $.post( "https://system.teamway.om/tasgeelxxx/index.php/upload/check_upload", { product_domain: "<?php echo  $folder_domain ?>" }, function( data ) {
    //     console.log( data.flag ); 
    //     if(!data.flag){
    //         $('#show_file').attr('href','https://system.teamway.om/tasgeelxxx/uploads/'+data.file);
    //         $('#show_file').css('display','block');
    //     }
    //     }, "json");

    // });
</script>    
