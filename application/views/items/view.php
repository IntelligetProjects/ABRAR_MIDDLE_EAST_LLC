<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('item_details'); ?></h1>
        </div>
    </div>
<?php 
$data= $item_info;
$stock = $data->purchased_qty - $data->invoiced_qty + $data->ad_qty - $data->purchase_return_qty + $data->sale_return_qty;
if($data->item_type == 'service') {
    $purchase = "-";
} else {
   $purchase = $data->purchased_qty ? $data->purchased_qty : 0;
}
if($data->item_type == 'service') {
    $deliver = "-";
} else {
   $deliver = $data->delivered_qty ? $data->delivered_qty : 0;
}
if($data->item_type == 'service') {
    $shipment = "-";
} else {
   $shipment = $data->shipment_qty ? $data->shipment_qty : 0;
}
if($data->item_type == 'service') {
    $ad = "-";
} else {
   $ad = $data->ad_qty ? $data->ad_qty : 0;
}
if($data->item_type == 'service') {
    $stocks = "-";
} else {
   $stocks = $stock ? $stock : 0;
}
// new 
$stocks =$shipment-$deliver+ $ad;
?>

    <table class="table">
        <tr><td><?php echo lang('id'); ?></td><td><?php echo $item_info->id ?></td></tr>
        <tr><td><?php echo lang('title'); ?></td><td><?php echo $item_info->title ?></td></tr>
        <tr><td><?php echo lang('description'); ?></td><td><?php echo $item_info->description ?></td></tr>
        <tr><td><?php echo lang('unit_type'); ?></td><td><?php echo $item_info->unit_type ?></td></tr>
        <tr><td><?php echo lang('cost'); ?></td><td><?php echo $item_info->cost ?></td></tr>
        <tr><td><?php echo lang('rate'); ?></td><td><?php echo $item_info->rate ?></td></tr>
        <!-- <tr><td><?php echo lang('category_id'); ?></td><td><?php echo $item_info->category_id ?></td></tr> -->
        <tr><td><?php echo lang('item_type'); ?></td><td><?php echo $item_info->item_type ?></td></tr>
        <tr><td><?php echo lang('stock'); ?></td><td><?php echo $stocks ?></td></tr>
        <?php if($this->db->dbprefix=='tarteeb_v3'){?>
        <tr><td><?php echo lang('max_stock'); ?></td><td><?php echo $item_info->max_stock ?></td></tr>
        <?php }?>
    </table>


    <?php
    if ($can_edit) { 
        $rowe = modal_anchor(get_uri("items/modal_form"), "<button class='btn btn-info'> ".lang('edit')." <i class='fa fa-pencil'></i></button>", array("class" => "edit", "title" => lang('edit_item'), "data-post-id" => $item_info->id));
        echo $rowe;
    }
    ?>