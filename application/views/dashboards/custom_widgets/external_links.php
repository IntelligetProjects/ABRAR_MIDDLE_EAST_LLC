<style>
    .links {
        color: white;
        font-size: 13px;
    }
    .panel-ashs {
        background-color: #1672b9;
    }
    .btn-defaulte {
        background-color: rgba(255,255,255,.15);
            color: #333;
    }
</style>
<div class="panel panel-ashs">
    <div class="white-link" >
        <div class="panel-body ">
            <div class="widget-details">
                <div style="font-weight: 600px"><?php echo lang("important_links"); ?></div>
            </div>
            <div>
                <?php if ($external_links) { ?>
                    <?php
                        $links = explode(",",$external_links);
                        foreach ($links as $link) {
                            if($link) {
                            echo "</br><a style='font-weight: 600px' class='links' "."href= http://".$link."># ".$link."</a>"; 
                            } 
                        }
                    ?>
                <?php } ?>
                </br>
                <div class="widget-details" style="padding-right: 5px;">
                    <div style="top: 51%; margin-top: -30%;">
                    <?php echo modal_anchor(get_uri("dashboard/links_form"), "<i class='fa fa-circle'></i> " . lang('change'), array("class" => "btn btn-defaulte", "title" => lang('change'))); ?>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</div>