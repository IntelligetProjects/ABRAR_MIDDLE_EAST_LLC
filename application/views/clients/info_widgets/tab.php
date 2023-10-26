<?php


$panel = "";
$icon = "";
$value = "";
if ($tab == "projects") {
    $panel = "panel-sky";
    $icon = "fa-th-large";
    $value = to_decimal_format($client_info->total_projects);
} else if ($tab == "invoice_value") {
    $panel = "panel-primary";
    $icon = "fa-file-text";
    $value = to_currency($sum_invoice_total, $client_info->currency_symbol);
} else if ($tab == "total_sales_return") {
    $panel = "panel-primary";
    $icon = "fa-file-text";
    $value = to_currency($total_sales_return, $client_info->currency_symbol);
} else if ($tab == "total_invoices") {
    $panel = "panel-primary";
    $icon = "fa-file-text";
    $value = to_currency($sum_invoice_total- $total_sales_return, $client_info->currency_symbol);
} else if ($tab == "payments") {
    $panel = "panel-success";
    $icon = "fa-check-square";
    $value = to_currency($sum_payments, $client_info->currency_symbol);
} else if ($tab == "due") {
    $panel = "panel-coral";
    $icon = "fa-money";
    $value = to_currency(ignor_minor_value($sum_invoice_total- $total_sales_return - $sum_payments), $client_info->currency_symbol);
}
?>
<style>
    .widget-icon {
    float: right;
    font-size: 19px;
    min-height: auto;
    opacity: 1;
    background: white;
    padding: 2px;
    border-radius: 100px;
    }
    h3 {
    font-size: 16px;
    }
    .widget-details {
    position: static;
    margin-left: 0px !important;
    }
    .panel-coral .widget-details {
    padding-right: 22px;
    margin: 0px;
}
.h4, h4 {
    font-size: 16px;
}
    </style>
<div class="panel <?php echo $panel ?>">
    <div class="panel-body ">
        <div class="widget-icon">
            <i class="fa <?php echo $icon; ?>"></i>
        </div>
        <div class="widget-details">
            <h3><?php echo $value; ?></h3>
            <h4><?php echo lang($tab); ?></h4>
        </div>
    </div>
</div>