<div id="page-content" class="p20 clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
        <div class="panel panel-default  p15 no-border clearfix">

            <div class="pull-right">
                <?php
                echo "<div class='text-center'>" . anchor("shipments/download_pdf/" . $shipment_info->id, lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>"
                ?>
            </div>

        </div>

        <?php if ($show_close_preview) {
            echo "<div class='text-center'>" . anchor("shipments/view/" . $shipment_info->id, lang("close_preview"), array("class" => "btn btn-default round")) . "</div>";
        }
        ?>

        <div class="invoice-preview-container bg-white mt15">

            <?php
            echo $shipment_preview;
            ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        
    });

</script>
