<?php if (get_setting("module_todo")) { ?>
    <li class="todo-top-icon">
        <a href="<?php echo_uri('todo'); ?>" class="dropdown-toggle top_tooltip" data-toggle="tooltip" data-original-title='<?=lang("manage_your_todo_list")?>' data-placement="bottom"><i class="fa fa-check-square-o"></i></a>
    </li>
<?php } ?>
