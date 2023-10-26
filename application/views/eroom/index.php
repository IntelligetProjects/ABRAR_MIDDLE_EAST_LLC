<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo lang('eroom') ?>
    </h1>
</div>

<div id="page-content" class="clearfix">

    <ul id="tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">

        <li><a  role="presentation" href="<?php echo_uri("eroom/files/" . 1); ?>" data-target="#one-files"><?php echo lang('files')." - 1"; ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("eroom/files/" . 2); ?>" data-target="#two-files"><?php echo lang('files')." - 2"; ?></a></li>

        <li><a  role="presentation" href="<?php echo_uri("eroom/files/" . 3); ?>" data-target="#three-files"><?php echo lang('files')." - 3"; ?></a></li>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="one-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="two-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="three-files"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
