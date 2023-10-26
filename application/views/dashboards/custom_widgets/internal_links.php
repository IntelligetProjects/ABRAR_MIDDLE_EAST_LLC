<?php 
    
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    
?>
<style>
    .panel-ashe{
        color: #fff;
    }
</style>
<div class="panel panel-ashe" style="background-color: <?= $color;?>;">
    <a href="<?php echo get_uri($module); ?>" class="white-link" >
        <div class="panel-body ">
            <div class="widget-icon">
                <i class="fa fa-compass"></i>
            </div>
            <div class="widget-details_internal_links">
                <?php echo lang($module); ?>
            </div>
        </div>
    </a>
</div>