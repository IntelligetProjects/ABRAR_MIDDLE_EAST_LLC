<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">


        <?php if ($show_close_preview) {
            echo "<div class='text-center'>" . anchor("purchase_orders/view/" . $purchase_order_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>";
        }
        ?>

        <div class="purchase_order-preview-container bg-white mt15">
            <div class="col-md-12">
                <div class="ribbon"><?php echo $purchase_order_status_label; ?></div>
            </div>

            <?php
            echo $purchase_order_preview;
            ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payment-amount").change(function () {
            var value = $(this).val();
            $(".payment-amount-field").each(function () {
                $(this).val(value);
            });
        });
    });



</script>
