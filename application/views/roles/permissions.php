<div class="tab-content">
    <?php echo form_open(get_uri("roles/save_permissions"), array("id" => "permissions-form", "class" => "general-form dashed-row", "role" => "form")); ?>
    <style>
        .app-loader
        {
            display:none !important;
        }
        .active:after
        {
            content:"" !important;
        }
        input[type='radio'] {
            -webkit-appearance:none;
            width:15px;
            height:15px;
            background:white;
            border-radius:10px;
            border:1px solid #555;
            }
            input[type='radio']:checked {
            background:  black;
            }
            input[type='checkbox'] {
            
            align-content: center;
            width:15px;
            height:15px;
            
            border-radius:4px;
            border:1px solid #555;
            }
            input[type='checkbox']:checked {
            
            }
            table, td, th {
                border: 1px solid #ddd;
                text-align: left;
            }
            td, th {
                padding: 10px;
            }   
            .accordion {
              background-color: #e0f3ff;
              color: #444;
              cursor: pointer;
              padding: 18px;
              width: 100%;
              border: none;
              text-align: left;
              outline: none;
              font-size: 20px;
              transition: 0.4s;
            }

            .active, .accordion:hover {
              background-color: #eee;
            }

            .accordion:after {
              content: '\002B';
              color: #777;
              font-weight: bold;
              float: right;
              margin-left: 5px;
            }

            .active:after {
              content: "\2212";
            }

            .pane {
              background-color: white;
              max-height: 0;
              overflow: hidden;
              overflow-x: scroll;
              overflow-y: scroll;
              transition: max-height 0.2s ease-out;
            }
    </style>
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class="panel">
            <div class="panel-default panel-heading">
                <h4><?php echo lang('permissions') . ": " . $model_info->title; ?></h4>
            </div>
            <div class="panel-body">

                 <ul class="permission-list">
                    
  
                </ul>

                <!-- kjlkjfjalskdjfskd projects -->
                <button class="accordion"><?php echo lang("project_management"); ?></button>
                <div class="pane">

                    <table id="table">
                        <tr>
                            <th><?= lang("module")?></th>
                            <th><?= lang("add")?></th>
                            <th><?= lang("edit")?></th>
                            <th><?= lang("delete")?></th>
                            <th><?= lang("can_manage_all_projects")?></th>
                        </tr>
                        <tr>
                            <td>
                                <label><?php echo lang("projects"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_projects", "1", $can_create_projects ? true : false, "id='can_create_projects' class='projectGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_projects", "1", $can_edit_projects ? true : false, "id='can_edit_projects' class='projectGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_projects", "1", $can_delete_projects ? true : false, "id='can_delete_projects' class='projectGrp'");
                                ?>
                            </td>
                            <td>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => 'can_manage_all_projects',
                                        "name" => "can_manage_all_projects",
                                        "value" => "1",
                                        "class" => "manage_permission toggle_specific",
                                            ), $can_manage_all_projects, ($can_manage_all_projects === "1") ? true : false);
                                    ?>
                                    <label for="can_manage_all_projects"><?php echo lang("yes_all"); ?></label>
                                </br>
                                <?php
                                    echo form_radio(array(
                                        "id" => 'can_manage_all_projects',
                                        "name" => "can_manage_all_projects",
                                        "value" => "0",
                                        "class" => "manage_permission toggle_specific",
                                            ), $can_manage_all_projects, ($can_manage_all_projects === "0") ? true : false);
                                    ?>
                                <label for="only_if_team_member"><?php echo lang("only_if_team_member"); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?php echo lang("project_tasks"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_tasks", "1", $can_create_tasks ? true : false, "id='can_create_tasks' class='taskGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_tasks", "1", $can_edit_tasks ? true : false, "id='can_edit_tasks' class='taskGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_tasks", "1", $can_delete_tasks ? true : false, "id='can_delete_tasks' class='taskGrp'");
                                ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label><?php echo lang("milestones"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_milestones", "1", $can_create_milestones ? true : false, "id='can_create_milestones' class='milestoneGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_milestones", "1", $can_edit_milestones ? true : false, "id='can_edit_milestones' class='milestoneGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_milestones", "1", $can_delete_milestones ? true : false, "id='can_delete_milestones' class='milestoneGrp'");
                                ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </table>

                    <table id = "table">
                        <tr>
                            <td>
                                <label><?php echo lang("other"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_add_remove_project_members", "1", $can_add_remove_project_members ? true : false, "id='can_add_remove_project_members' class='taskGrp'");
                                ?>
                                <label for="can_add_remove_project_members"><?php echo lang("can_add_remove_project_members"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_files", "1", $can_delete_files ? true :false, "id='can_delete_files' class='taskGrp'");
                                ?>
                                <label for="can_delete_files"><?php echo lang("can_delete_files"); ?></label>
                            </td>
                            <td colspan="2"><?php
                                echo form_checkbox("can_comment_on_tasks", "1", $can_comment_on_tasks ? true : false, "id='can_comment_on_tasks' class='taskGrp'");
                                ?>
                                <label for="can_comment_on_tasks"><?php echo lang("can_comment_on_tasks"); ?></label>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td>
                                <?php echo lang("can_manage_team_members_project_timesheet"); ?>
                            </td>
                            <td>
                                <?php
                                echo form_radio(array(
                                    "id" => "timesheet_manage_permission_no",
                                    "name" => "timesheet_manage_permission",
                                    "value" => "",
                                    "class" => "timesheet_manage_permission toggle_specific",
                                        ), $timesheet_manage_permission, ($timesheet_manage_permission === "") ? true : false);
                                ?>
                                <label for="timesheet_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </td>
                            <td>
                                <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "timesheet_manage_permission_all",
                                    "name" => "timesheet_manage_permission",
                                    "value" => "all",
                                    "class" => "timesheet_manage_permission toggle_specific",
                                        ), $timesheet_manage_permission, ($timesheet_manage_permission === "all") ? true : false);
                                ?>
                                <label for="timesheet_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                            </td>
                            <td>
                                <?php
                                echo form_radio(array(
                                    "id" => "timesheet_manage_permission_specific",
                                    "name" => "timesheet_manage_permission",
                                    "value" => "specific",
                                    "class" => "timesheet_manage_permission toggle_specific",
                                        ), $timesheet_manage_permission, ($timesheet_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="timesheet_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                             <div class="specific_dropdown">
                                <input type="text" value="<?php echo $timesheet_manage_permission_specific; ?>" name="timesheet_manage_permission_specific" id="timesheet_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                            </div>   
                            </td>
                        </tr> -->
                    </table>
                </div>

                <!-- kjlkjfjalskdjfskd saless -->
                <button class="accordion"><?php echo lang("sales"); ?></button>
                <div class="pane">
                    <table id = "table">
                        <tr>
                            <th><?= lang("access")?></th>
                            <th><?= lang("add")?></th>
                            <th><?= lang("edit")?></th>
                            <th><?= lang("delete")?></th>
                            <th><?= lang("can_manage_other_records")?></th>
                        </tr>
                            <!-- <tr>
                            <td><?php
                                echo form_checkbox("can_access_contacts", "1", $can_access_contacts ? true : false, "id='can_access_contacts' class='can_access_contacts'");
                                ?>
                                <label for="can_access_contacts"><?php echo lang("contacts"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_contacts", "1", $can_create_contacts ? true : false, "id='can_create_contacts' class='contactGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_contacts", "1", $can_edit_contacts ? true : false, "id='can_edit_contacts' class='contactGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_contacts", "1", $can_delete_contacts ? true : false, "id='can_delete_contacts' class='contactGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "contact_manage_permission_no",
                                "name" => "contact_manage_permission",
                                "value" => "",
                                "class" => "contact_manage_permission toggle_specific",
                                    ), $contact_manage_permission, ($contact_manage_permission === "") ? true : false);
                            ?>
                            <label for="contact_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "contact_manage_permission_all",
                                    "name" => "contact_manage_permission",
                                    "value" => "all",
                                    "class" => "contact_manage_permission toggle_specific",
                                        ), $contact_manage_permission, ($contact_manage_permission === "all") ? true : false);
                                ?>
                                <label for="contact_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "contact_manage_permission_specific",
                                    "name" => "contact_manage_permission",
                                    "value" => "specific",
                                    "class" => "contact_manage_permission toggle_specific",
                                        ), $contact_manage_permission, ($contact_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="contact_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $contact_manage_permission_specific; ?>" name="contact_manage_permission_specific" id="contact_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr> -->

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_clients", "1", $can_access_clients ? true : false, "id='can_access_clients' class='can_access_clients'");
                                ?>
                                <label for="can_access_clients"><?php echo lang("clients"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_clients", "1", $can_create_clients ? true : false, "id='can_create_clients' class='clientGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_clients", "1", $can_edit_clients ? true : false, "id='can_edit_clients' class='clientGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_clients", "1", $can_delete_clients ? true : false, "id='can_delete_clients' class='clientGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "client_manage_permission_no",
                                "name" => "client_manage_permission",
                                "value" => "",
                                "class" => "client_manage_permission toggle_specific",
                                    ), $client_manage_permission, ($client_manage_permission === "") ? true : false);
                            ?>
                            <label for="client_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "client_manage_permission_all",
                                    "name" => "client_manage_permission",
                                    "value" => "all",
                                    "class" => "client_manage_permission toggle_specific",
                                        ), $client_manage_permission, ($client_manage_permission === "all") ? true : false);
                                ?>
                                <label for="client_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "client_manage_permission_specific",
                                    "name" => "client_manage_permission",
                                    "value" => "specific",
                                    "class" => "client_manage_permission toggle_specific",
                                        ), $client_manage_permission, ($client_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="client_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $client_manage_permission_specific; ?>" name="client_manage_permission_specific" id="client_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_leads", "1", $can_access_leads ? true : false, "id='can_access_leads' class='can_access_leads'");
                                ?>
                                <label for="can_access_leads"><?php echo lang("leads"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_leads", "1", $can_create_leads ? true : false, "id='can_create_leads' class='leadsGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_leads", "1", $can_edit_leads ? true : false, "id='can_edit_leads' class='leadsGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_leads", "1", $can_delete_leads ? true : false, "id='can_delete_leads' class='leadsGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "leads_manage_permission_no",
                                "name" => "leads_manage_permission",
                                "value" => "",
                                "class" => "leads_manage_permission toggle_specific",
                                    ), $leads_manage_permission, ($leads_manage_permission === "") ? true : false);
                            ?>
                            <label for="leads_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "leads_manage_permission_all",
                                    "name" => "leads_manage_permission",
                                    "value" => "all",
                                    "class" => "leads_manage_permission toggle_specific",
                                        ), $leads_manage_permission, ($leads_manage_permission === "all") ? true : false);
                                ?>
                                <label for="leads_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "leads_manage_permission_specific",
                                    "name" => "leads_manage_permission",
                                    "value" => "specific",
                                    "class" => "leads_manage_permission toggle_specific",
                                        ), $leads_manage_permission, ($leads_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="leads_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $leads_manage_permission_specific; ?>" name="leads_manage_permission_specific" id="leads_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>

                            <tr>
                                <td><?php
                                    echo form_checkbox("can_access_invoices", "1", $can_access_invoices ? true : false, "id='can_access_invoices' class='can_access_invoices'");
                                    ?>
                                    <label for="can_access_invoices"><?php echo lang("invoices"); ?></label>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_create_invoices", "1", $can_create_invoices ? true : false, "id='can_create_invoices' class='invoiceGrp'");
                                    ?>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_edit_invoices", "1", $can_edit_invoices ? true : false, "id='can_edit_invoices' class='invoiceGrp'");
                                    ?>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_delete_invoices", "1", $can_delete_invoices ? true : false, "id='can_delete_invoices' class='invoiceGrp'");
                                    ?>
                                </td>
                                <td>
                                <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "invoice_manage_permission_no",
                                    "name" => "invoice_manage_permission",
                                    "value" => "",
                                    "class" => "invoice_manage_permission toggle_specific",
                                        ), $invoice_manage_permission, ($invoice_manage_permission === "") ? true : false);
                                ?>
                                <label for="invoice_manage_permission_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_manage_permission_all",
                                        "name" => "invoice_manage_permission",
                                        "value" => "all",
                                        "class" => "invoice_manage_permission toggle_specific",
                                            ), $invoice_manage_permission, ($invoice_manage_permission === "all") ? true : false);
                                    ?>
                                    <label for="invoice_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                </div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_manage_permission_specific",
                                        "name" => "invoice_manage_permission",
                                        "value" => "specific",
                                        "class" => "invoice_manage_permission toggle_specific",
                                            ), $invoice_manage_permission, ($invoice_manage_permission === "specific") ? true : false);
                                    ?>
                                    <label for="invoice_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                    </div>
                                    <div class="specific_dropdown">
                                        <input type="text" value="<?php echo $invoice_manage_permission_specific; ?>" name="invoice_manage_permission_specific" id="invoice_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php
                                    echo form_checkbox("can_access_invoices_return", "1", $can_access_invoices_return ? true : false, "id='can_access_invoices_return' class='can_access_invoices_return'");
                                    ?>
                                    <label for="can_access_invoices_return"><?php echo lang("invoices_sales_return"); ?></label>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_create_invoices_return", "1", $can_create_invoices_return ? true : false, "id='can_create_invoices_return' class='invoice_returnGrp'");
                                    ?>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_edit_invoices_return", "1", $can_edit_invoices_return ? true : false, "id='can_edit_invoices_return' class='invoice_returnGrp'");
                                    ?>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_delete_invoices_return", "1", $can_delete_invoices_return ? true : false, "id='can_delete_invoices_return' class='invoice_returnGrp'");
                                    ?>
                                </td>
                                <td>
                                <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "invoice_return_manage_permission_no",
                                    "name" => "invoice_return_manage_permission",
                                    "value" => "",
                                    "class" => "invoice_return_manage_permission toggle_specific",
                                        ), $invoice_return_manage_permission, ($invoice_return_manage_permission === "") ? true : false);
                                ?>
                                <label for="invoice_return_manage_permission_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_return_manage_permission_all",
                                        "name" => "invoice_return_manage_permission",
                                        "value" => "all",
                                        "class" => "invoice_return_manage_permission toggle_specific",
                                            ), $invoice_return_manage_permission, ($invoice_return_manage_permission === "all") ? true : false);
                                    ?>
                                    <label for="invoice_return_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                </div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_return_manage_permission_specific",
                                        "name" => "invoice_return_manage_permission",
                                        "value" => "specific",
                                        "class" => "invoice_return_manage_permission toggle_specific",
                                            ), $invoice_return_manage_permission, ($invoice_return_manage_permission === "specific") ? true : false);
                                    ?>
                                    <label for="invoice_return_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                    </div>
                                    <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $invoice_return_manage_permission_specific; ?>" name="invoice_return_manage_permission_specific" id="invoice_return_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td><?php
                                    echo form_checkbox("can_access_estimates", "1", $can_access_estimates ? true : false, "id='can_access_estimates' class='can_access_estimates'");
                                    ?>
                                    <label for="can_access_estimates"><?php echo lang("estimates"); ?></label>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_create_estimates", "1", $can_create_estimates ? true : false, "id='can_create_estimates' class='estimateGrp'");
                                    ?>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_edit_estimates", "1", $can_edit_estimates ? true : false, "id='can_edit_estimates' class='estimateGrp'");
                                    ?>
                                </td>
                                <td><?php
                                    echo form_checkbox("can_delete_estimates", "1", $can_delete_estimates ? true : false, "id='can_delete_estimates' class='estimateGrp'");
                                    ?>
                                </td>
                                <td>
                                <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "estimate_manage_permission_no",
                                    "name" => "estimate_manage_permission",
                                    "value" => "",
                                    "class" => "estimate_manage_permission toggle_specific",
                                        ), $estimate_manage_permission, ($estimate_manage_permission === "") ? true : false);
                                ?>
                                <label for="estimate_manage_permission_no"><?php echo lang("no"); ?> </label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimate_manage_permission_all",
                                        "name" => "estimate_manage_permission",
                                        "value" => "all",
                                        "class" => "estimate_manage_permission toggle_specific",
                                            ), $estimate_manage_permission, ($estimate_manage_permission === "all") ? true : false);
                                    ?>
                                    <label for="estimate_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                </div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimate_manage_permission_specific",
                                        "name" => "estimate_manage_permission",
                                        "value" => "specific",
                                        "class" => "estimate_manage_permission toggle_specific",
                                            ), $estimate_manage_permission, ($estimate_manage_permission === "specific") ? true : false);
                                    ?>
                                    <label for="estimate_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                    </div>
                                    <div class="specific_dropdown">
                                        <input type="text" value="<?php echo $estimate_manage_permission_specific; ?>" name="estimate_manage_permission_specific" id="estimate_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                    </div>
                                </td>
                            </tr>

                        <table id=table>
                                <label for="discount" class=" col-md-3"><?php echo lang('discount_percentage_limit'); ?></label>
                                <div class="col-md-9">
                                    <?php
                                    echo form_input(array(
                                        "id" => "discount",
                                        "name" => "discount",
                                        "value" => $discount,
                                        "class" => "form-control",
                                        "placeholder" => lang('discount_percentage'),                
                                    ));
                                    ?>
                                </div>
                        </table>

                        <!-- <table id=table>
                        <tr>
                            <td>
                                <?php echo lang("can_manage_estimate_requests_forms"); ?>
                            </td>
                            <td colspan="2">
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimate_request_no",
                                        "name" => "estimate_request",
                                        "value" => "",
                                            ), $estimate_request, ($estimate_request === "") ? true : false);
                                    ?>
                                    <label for="estimate_request_no"><?php echo lang("no"); ?> </label>
                                </div>
                            </td>
                            <td colspan="2">
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimate_request_yes",
                                        "name" => "estimate_request",
                                        "value" => "all",
                                            ), $estimate_request, ($estimate_request === "all") ? true : false);
                                    ?>
                                    <label for="estimate_request_yes"><?php echo lang("yes"); ?></label>
                                </div>
                            </td>
                        </tr>
                    </table> -->

                    <table id=table>
                        <tr>
                            <td>
                                <?php echo lang("can_access_leads_information"); ?>
                            </td>
                            <td colspan="2">
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "lead_no",
                                        "name" => "lead_permission",
                                        "value" => "",
                                            ), $lead, ($lead === "") ? true : false);
                                    ?>
                                    <label for="lead_no"><?php echo lang("no"); ?> </label>
                                </div>
                            </td>
                            <td colspan="2">
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "lead_yes",
                                        "name" => "lead_permission",
                                        "value" => "all",
                                            ), $lead, ($lead === "all") ? true : false);
                                    ?>
                                    <label for="lead_yes"><?php echo lang("yes"); ?></label>
                                </div>
                            </td>
                        </tr>
                        </table>
                </div>

                <!-- kjlkjfjalskdjfskd inventroy -->
                <button class="accordion"><?php echo lang("inventory"); ?></button>
                <div class="pane">

                    <table id="table">
                    
                        <tr>
                            <th><?= lang("access")?></th>
                            <th><?= lang("add")?></th>
                            <th><?= lang("edit")?></th>
                            <th><?= lang("delete")?></th>
                            <th><?= lang("can_manage_other_records")?></th>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_suppliers", "1", $can_access_suppliers ? true : false, "id='can_access_suppliers' class='can_access_suppliers'");
                                ?>
                                <label for="can_access_suppliers"><?php echo lang("suppliers"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_suppliers", "1", $can_create_suppliers ? true : false, "id='can_create_suppliers' class='supplierGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_suppliers", "1", $can_edit_suppliers ? true : false, "id='can_edit_suppliers' class='supplierGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_suppliers", "1", $can_delete_suppliers ? true : false, "id='can_delete_suppliers' class='supplierGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "supplier_manage_permission_no",
                                "name" => "supplier_manage_permission",
                                "value" => "",
                                "class" => "supplier_manage_permission toggle_specific",
                                    ), $supplier_manage_permission, ($supplier_manage_permission === "") ? true : false);
                            ?>
                            <label for="supplier_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "supplier_manage_permission_all",
                                    "name" => "supplier_manage_permission",
                                    "value" => "all",
                                    "class" => "supplier_manage_permission toggle_specific",
                                        ), $supplier_manage_permission, ($supplier_manage_permission === "all") ? true : false);
                                ?>
                                <label for="supplier_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "supplier_manage_permission_specific",
                                    "name" => "supplier_manage_permission",
                                    "value" => "specific",
                                    "class" => "supplier_manage_permission toggle_specific",
                                        ), $supplier_manage_permission, ($supplier_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="supplier_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $supplier_manage_permission_specific; ?>" name="supplier_manage_permission_specific" id="supplier_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_purchase_orders", "1", $can_access_purchase_orders ? true : false, "id='can_access_purchase_orders' class='can_access_purchase_orders'");
                                ?>
                                <label for="can_access_purchase_orders"><?php echo lang("purchase_orders").', '.lang('shipment_and_purchase_return'); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_purchase_orders", "1", $can_create_purchase_orders ? true : false, "id='can_create_purchase_orders' class='purchase_orderGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_purchase_orders", "1", $can_edit_purchase_orders ? true : false, "id='can_edit_purchase_orders' class='purchase_orderGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_purchase_orders", "1", $can_delete_purchase_orders ? true : false, "id='can_delete_purchase_orders' class='purchase_orderGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "purchase_order_manage_permission_no",
                                "name" => "purchase_order_manage_permission",
                                "value" => "",
                                "class" => "purchase_order_manage_permission toggle_specific",
                                    ), $purchase_order_manage_permission, ($purchase_order_manage_permission === "") ? true : false);
                            ?>
                            <label for="purchase_order_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "purchase_order_manage_permission_all",
                                    "name" => "purchase_order_manage_permission",
                                    "value" => "all",
                                    "class" => "purchase_order_manage_permission toggle_specific",
                                        ), $purchase_order_manage_permission, ($purchase_order_manage_permission === "all") ? true : false);
                                ?>
                                <label for="purchase_order_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "purchase_order_manage_permission_specific",
                                    "name" => "purchase_order_manage_permission",
                                    "value" => "specific",
                                    "class" => "purchase_order_manage_permission toggle_specific",
                                        ), $purchase_order_manage_permission, ($purchase_order_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="purchase_order_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $purchase_order_manage_permission_specific; ?>" name="purchase_order_manage_permission_specific" id="purchase_order_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>

                        <!-- <tr>
                            <td><?php
                                echo form_checkbox("can_access_dispatches", "1", $can_access_dispatches ? true : false, "id='can_access_dispatches' class='can_access_dispatches'");
                                ?>
                                <label for="can_access_dispatches"><?php echo lang("dispatches"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_dispatches", "1", $can_create_dispatches ? true : false, "id='can_create_dispatches' class='dispatcheGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_dispatches", "1", $can_edit_dispatches ? true : false, "id='can_edit_dispatches' class='dispatcheGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_dispatches", "1", $can_delete_dispatches ? true : false, "id='can_delete_dispatches' class='dispatcheGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "dispatch_manage_permission_no",
                                "name" => "dispatch_manage_permission",
                                "value" => "",
                                "class" => "dispatch_manage_permission toggle_specific",
                                    ), $dispatch_manage_permission, ($dispatch_manage_permission === "") ? true : false);
                            ?>
                            <label for="dispatch_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "dispatch_manage_permission_all",
                                    "name" => "dispatch_manage_permission",
                                    "value" => "all",
                                    "class" => "dispatch_manage_permission toggle_specific",
                                        ), $dispatch_manage_permission, ($dispatch_manage_permission === "all") ? true : false);
                                ?>
                                <label for="dispatch_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "dispatch_manage_permission_specific",
                                    "name" => "dispatch_manage_permission",
                                    "value" => "specific",
                                    "class" => "dispatch_manage_permission toggle_specific",
                                        ), $dispatch_manage_permission, ($dispatch_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="dispatch_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $dispatch_manage_permission_specific; ?>" name="dispatch_manage_permission_specific" id="dispatch_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr> -->

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_delivery_notes", "1", $can_access_delivery_notes ? true : false, "id='can_access_delivery_notes' class='can_access_delivery_notes'");
                                ?>
                                <label for="can_access_delivery_notes"><?php echo lang("delivery_notes"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_delivery_notes", "1", $can_create_delivery_notes ? true : false, "id='can_create_delivery_notes' class='delivery_noteGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_delivery_notes", "1", $can_edit_delivery_notes ? true : false, "id='can_edit_delivery_notes' class='delivery_noteGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_delivery_notes", "1", $can_delete_delivery_notes ? true : false, "id='can_delete_delivery_notes' class='delivery_noteGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "delivery_note_manage_permission_no",
                                "name" => "delivery_note_manage_permission",
                                "value" => "",
                                "class" => "delivery_note_manage_permission toggle_specific",
                                    ), $delivery_note_manage_permission, ($delivery_note_manage_permission === "") ? true : false);
                            ?>
                            <label for="delivery_note_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "delivery_note_manage_permission_all",
                                    "name" => "delivery_note_manage_permission",
                                    "value" => "all",
                                    "class" => "delivery_note_manage_permission toggle_specific",
                                        ), $delivery_note_manage_permission, ($delivery_note_manage_permission === "all") ? true : false);
                                ?>
                                <label for="delivery_note_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "delivery_note_manage_permission_specific",
                                    "name" => "delivery_note_manage_permission",
                                    "value" => "specific",
                                    "class" => "delivery_note_manage_permission toggle_specific",
                                        ), $delivery_note_manage_permission, ($delivery_note_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="delivery_note_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $delivery_note_manage_permission_specific; ?>" name="delivery_note_manage_permission_specific" id="delivery_note_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_items", "1", $can_access_items ? true : false, "id='can_access_items' class='can_access_items'");
                                ?>
                                <label for="can_access_items"><?php echo lang("items"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_items", "1", $can_create_items ? true : false, "id='can_create_items' class='itemGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_items", "1", $can_edit_items ? true : false, "id='can_edit_items' class='itemGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_items", "1", $can_delete_items ? true : false, "id='can_delete_items' class='itemGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "item_manage_permission_no",
                                "name" => "item_manage_permission",
                                "value" => "",
                                "class" => "item_manage_permission toggle_specific",
                                    ), $item_manage_permission, ($item_manage_permission === "") ? true : false);
                            ?>
                            <label for="item_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "item_manage_permission_all",
                                    "name" => "item_manage_permission",
                                    "value" => "all",
                                    "class" => "item_manage_permission toggle_specific",
                                        ), $item_manage_permission, ($item_manage_permission === "all") ? true : false);
                                ?>
                                <label for="item_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "item_manage_permission_specific",
                                    "name" => "item_manage_permission",
                                    "value" => "specific",
                                    "class" => "item_manage_permission toggle_specific",
                                        ), $item_manage_permission, ($item_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="item_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $item_manage_permission_specific; ?>" name="item_manage_permission_specific" id="item_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_items_category", "1", $can_access_items_category ? true : false, "id='can_access_items_category' class='can_access_items_category'");
                                ?>
                                <label for="can_access_items_category"><?php echo lang("items_category"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_items_category", "1", $can_create_items_category ? true : false, "id='can_create_items_category' class='itemCategoryGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_items_category", "1", $can_edit_items_category ? true : false, "id='can_edit_items_category' class='itemCategoryGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_items_category", "1", $can_delete_items_category ? true : false, "id='can_delete_items_category' class='itemCategoryGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "item_category_manage_permission_no",
                                "name" => "item_category_manage_permission",
                                "value" => "",
                                "class" => "item_manage_permission toggle_specific",
                                    ), $item_category_manage_permission, ($item_category_manage_permission === "") ? true : false);
                            ?>
                            <label for="item_category_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "item_category_manage_permission_all",
                                    "name" => "item_category_manage_permission",
                                    "value" => "all",
                                    "class" => "item_category_manage_permission toggle_specific",
                                        ), $item_category_manage_permission, ($item_category_manage_permission === "all") ? true : false);
                                ?>
                                <label for="item_category_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "item_category_manage_permission_specific",
                                    "name" => "item_category_manage_permission",
                                    "value" => "specific",
                                    "class" => "item_category_manage_permission toggle_specific",
                                        ), $item_category_manage_permission, ($item_category_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="item_category_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $item_category_manage_permission_specific; ?>" name="item_category_manage_permission_specific" id="item_category_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>
                        <!--<tr>
                            <td><?php
                                echo form_checkbox("can_access_shipments", "1", $can_access_shipments ? true : false, "id='can_access_shipments' class='can_access_shipments'");
                                ?>
                                <label for="can_access_shipments"><?php echo lang("shipments"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_shipments", "1", $can_create_shipments ? true : false, "id='can_create_shipments' class='shipmentGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_shipments", "1", $can_edit_shipments ? true : false, "id='can_edit_shipments' class='shipmentGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_shipments", "1", $can_delete_shipments ? true : false, "id='can_delete_shipments' class='shipmentGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "shipment_manage_permission_no",
                                "name" => "shipment_manage_permission",
                                "value" => "",
                                "class" => "shipment_manage_permission toggle_specific",
                                    ), $shipment_manage_permission, ($shipment_manage_permission === "") ? true : false);
                            ?>
                            <label for="shipment_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "shipment_manage_permission_all",
                                    "name" => "shipment_manage_permission",
                                    "value" => "all",
                                    "class" => "shipment_manage_permission toggle_specific",
                                        ), $shipment_manage_permission, ($shipment_manage_permission === "all") ? true : false);
                                ?>
                                <label for="shipment_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "shipment_manage_permission_specific",
                                    "name" => "shipment_manage_permission",
                                    "value" => "specific",
                                    "class" => "shipment_manage_permission toggle_specific",
                                        ), $shipment_manage_permission, ($shipment_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="shipment_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $shipment_manage_permission_specific; ?>" name="shipment_manage_permission_specific" id="shipment_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr> -->
                    </table>
                </div>

                <!-- kjlkjfjalskdjfskd finance -->
                <button class="accordion"><?php echo lang("finance"); ?></button>
                <div class="pane">

                    <table id = "table">

                        <tr>
                            <th><?= lang("access")?></th>
                            <th><?= lang("add")?></th>
                            <th><?= lang("edit")?></th>
                            <th><?= lang("delete")?></th>
                            <th><?= lang("can_manage_other_records")?></th>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_expenses", "1", $can_access_expenses ? true : false, "id='can_access_expenses' class='can_access_expenses'");
                                ?>
                                <label for="can_access_expenses"><?php echo lang("expenses"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_expenses", "1", $can_create_expenses ? true : false, "id='can_create_expenses' class='expensesGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_expenses", "1", $can_edit_expenses ? true : false, "id='can_edit_expenses' class='expensesGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_expenses", "1", $can_delete_expenses ? true : false, "id='can_delete_expenses' class='expensesGrp'");
                                ?>
                            </td>
                            <td>
                            <div>
                            <?php
                            echo form_radio(array(
                                "id" => "expense_manage_permission_no",
                                "name" => "expense_manage_permission",
                                "value" => "",
                                "class" => "expense_manage_permission toggle_specific",
                                    ), $expense_manage_permission, ($expense_manage_permission === "") ? true : false);
                            ?>
                            <label for="expense_manage_permission_no"><?php echo lang("no"); ?> </label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "expense_manage_permission_all",
                                    "name" => "expense_manage_permission",
                                    "value" => "all",
                                    "class" => "expense_manage_permission toggle_specific",
                                        ), $expense_manage_permission, ($expense_manage_permission === "all") ? true : false);
                                ?>
                                <label for="expense_manage_permission_all"><?php echo lang("yes_all_members"); ?></label>
                            </div>
                                <?php
                                echo form_radio(array(
                                    "id" => "expense_manage_permission_specific",
                                    "name" => "expense_manage_permission",
                                    "value" => "specific",
                                    "class" => "expense_manage_permission toggle_specific",
                                        ), $expense_manage_permission, ($expense_manage_permission === "specific") ? true : false);
                                ?>
                                <label for="expense_manage_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                </div>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $expense_manage_permission_specific; ?>" name="expense_manage_permission_specific" id="expense_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo ('Choose Members / Teams'); ?>"  />
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><h5><?php echo lang("can_access_account_settings"); ?> ?</h5></td>
                            <td>
                            <?php
                            echo form_radio(array(
                                "id" => "account_setting_no",
                                "name" => "account_setting",
                                "value" => "",
                                    ), $account_setting, ($account_setting === "") ? true : false);
                            ?>
                            <label for="account_setting"><?php echo lang("no"); ?> </label>
                            </td> 
                            <td>
                                <?php
                            echo form_radio(array(
                                "id" => "account_setting_yes",
                                "name" => "account_setting",
                                "value" => "all",
                                    ), $account_setting, ($account_setting === "all") ? true : false);
                            ?>
                            <label for="account_setting_yes"><?php echo lang("yes"); ?></label>
                        </td> 
                        </tr>
                        
                    
                        <tr>
                            <td> <h5><?php echo lang("can_access_expiries"); ?>?</h5></td>
                            <td>
                            <?php
                            echo form_radio(array(
                                "id" => "expiries_no",
                                "name" => "expiries_permission",
                                "value" => "",
                                    ), $expiries, ($expiries === "") ? true : false);
                            ?>
                            <label for="expiries_no"><?php echo lang("no"); ?> </label>
                        </td> 
                            <td>
                            <?php
                            echo form_radio(array(
                                "id" => "expiries_yes",
                                "name" => "expiries_permission",
                                "value" => "all",
                                    ), $expiries, ($expiries === "all") ? true : false);
                            ?>
                            <label for="expiries_yes"><?php echo lang("yes"); ?></label>
                        </td> 
                        </tr>

                       
                    </table>

                    <!-- <table>
                        <tr>
                            <td>
                                <?php echo lang("can_access_internal_transactions"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "internal_transaction_no",
                                            "name" => "internal_transaction",
                                            "value" => "",
                                                ), $internal_transaction, ($internal_transaction === "") ? true : false);
                                        ?>
                                        <label for="internal_transaction_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td >
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "internal_transaction_yes",
                                            "name" => "internal_transaction",
                                            "value" => "all",
                                                ), $internal_transaction, ($internal_transaction === "all") ? true : false);
                                        ?>
                                        <label for="internal_transaction_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>
                    </table> -->

                    <table id = "table">

                        <tr>
                            <th><?= lang("access")?></th>
                            <th><?= lang("add")?></th>
                            <th><?= lang("edit")?></th>
                            <th><?= lang("delete")?></th>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_invoice_payments", "1", $can_access_invoice_payments ? true : false, "id='can_access_invoice_payments' class='can_access_invoice_payments'");
                                ?>
                                <label for="can_access_invoice_payments"><?php echo lang("invoice_payments"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_invoice_payments", "1", $can_create_invoice_payments ? true : false, "id='can_create_invoice_payments' class='invoice_paymentGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_invoice_payments", "1", $can_edit_invoice_payments ? true : false, "id='can_edit_invoice_payments' class='invoice_paymentGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_invoice_payments", "1", $can_delete_invoice_payments ? true : false, "id='can_delete_invoice_payments' class='invoice_paymentGrp'");
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td><?php
                                echo form_checkbox("can_access_purchase_order_payments", "1", $can_access_purchase_order_payments ? true : false, "id='can_access_purchase_order_payments' class='can_access_purchase_order_payments'");
                                ?>
                                <label for="can_access_purchase_order_payments"><?php echo lang("purchase_order_payments"); ?></label>
                            </td>
                            <td><?php
                                echo form_checkbox("can_create_purchase_order_payments", "1", $can_create_purchase_order_payments ? true : false, "id='can_create_purchase_order_payments' class='purchase_order_paymentGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_edit_purchase_order_payments", "1", $can_edit_purchase_order_payments ? true : false, "id='can_edit_purchase_order_payments' class='purchase_order_paymentGrp'");
                                ?>
                            </td>
                            <td><?php
                                echo form_checkbox("can_delete_purchase_order_payments", "1", $can_delete_purchase_order_payments ? true : false, "id='can_delete_purchase_order_payments' class='purchase_order_paymentGrp'");
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- kjlkjfjalskdjfskd hr -->
                <button class="accordion"><?php echo lang("hr"); ?></button>
                <div class="pane">
                    <table id ="table">
                        <tr>
                            <th></th>
                            <th><?= lang("hide_team_members_list")?></th>
                            <th><?= lang("can_view_team_members_contact_info")?></th>
                            <th><?= lang("can_view_team_members_social_links")?></th>
                            <th><?= lang("can_add_team_member")?></th>
                            <!-- <th><?= lang("can_update_team_members_general_info_and_social_links")?></th> -->
                        </tr>
                        <tr>
                            <td>
                                <label><?php echo lang("team_member_permissions"); ?></label>
                            </td>
                            <td><?php
                             echo form_checkbox("hide_team_members_list", "1", $hide_team_members_list ? true : false, "id='hide_team_members_list'");
                             ?>
                            </td>
                            <td><?php
                             echo form_checkbox("can_view_team_members_contact_info", "1", $can_view_team_members_contact_info ? true : false, "id='can_view_team_members_contact_info'");
                             ?>
                            </td>
                            <!-- <td><?php
                                //echo form_checkbox("can_view_team_members_social_links", "1", $can_view_team_members_social_links ? true : false, "id='can_view_team_members_social_links'");
                                ?>
                            </td> -->
                                <td><div>
                                    <?php
                                     echo form_radio(array(
                                        "id" => "team_member_update_permission_no",
                                        "name" => "team_member_update_permission",
                                        "value" => "",
                                        "class" => "team_member_update_permission toggle_specific",
                                            ), $team_member_update_permission, ($team_member_update_permission === "") ? true : false);

                                  ?>
                                    <label for="team_member_update_permission_no"><?php echo lang("no"); ?></label>
                                    </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "team_member_update_permission_all",
                                        "name" => "team_member_update_permission",
                                        "value" => "all",
                                        "class" => "team_member_update_permission toggle_specific",
                                            ), $team_member_update_permission, ($team_member_update_permission === "all") ? true : false);
                                    ?>
                                    <label for="team_member_update_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                </div>
                                <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "team_member_update_permission_specific",
                                        "name" => "team_member_update_permission",
                                        "value" => "specific",
                                        "class" => "team_member_update_permission toggle_specific",
                                            ), $team_member_update_permission, ($team_member_update_permission === "specific") ? true : false);
                                    ?>
                                    <label for="team_member_update_permission_specific"><?php echo lang("yes_specific_members_or_teams"); ?>:</label>
                                    <div class="specific_dropdown">
                                        <input type="text" value="<?php echo $team_member_update_permission_specific; ?>" name="team_member_update_permission_specific" id="team_member_update_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                                    </div>
                                </td>
                                <td><?php
                             echo form_checkbox("can_add_team_member", "1", $can_add_team_member ? true : false, "id='can_add_team_member'");
                             ?>
                            </td>
                        </tr>
                        </table>

                        <table>
<!--                             <tr>
                            <td>
                                <?php echo lang("can_access_tickets"); ?>
                                </td>
                                <td>
                                    <div>
                                            <?php
                                            echo form_radio(array(
                                                "id" => "ticket_permission_no",
                                                "name" => "ticket_permission",
                                                "value" => "",
                                                "class" => "ticket_permission toggle_specific",
                                                    ), $ticket, ($ticket === "") ? true : false);
                                            ?>
                                            <label for="ticket_permission_no"><?php echo lang("no"); ?> </label>
                                        </div>
                                </td>
                                <td>
                                    <div>
                                            <?php
                                            echo form_radio(array(
                                                "id" => "ticket_permission_all",
                                                "name" => "ticket_permission",
                                                "value" => "all",
                                                "class" => "ticket_permission toggle_specific",
                                                    ), $ticket, ($ticket === "all") ? true : false);
                                            ?>
                                            <label for="ticket_permission_all"><?php echo lang("yes_all_tickets"); ?></label>
                                        </div>
                                </td>
                                <td>
                                  <div class="form-group">
                                        <?php
                                        echo form_radio(array(
                                            "id" => "ticket_permission_specific",
                                            "name" => "ticket_permission",
                                            "value" => "specific",
                                            "class" => "ticket_permission toggle_specific",
                                                ), $ticket, ($ticket === "specific") ? true : false);
                                        ?>
                                        <label for="ticket_permission_specific"><?php echo lang("yes_specific_ticket_types"); ?>:</label>
                                        <div class="specific_dropdown">
                                            <input type="text" value="<?php echo $ticket_specific; ?>" name="ticket_permission_specific" id="ticket_types_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_ticket_types'); ?>"  />
                                        </div>
                                    </div>
                                </td>
                            </tr> -->
                            <!-- <tr>
                            <td>
                                <?php echo lang("can_manage_team_members_timecards"); ?> <span class="help" data-toggle="tooltip" title="Add, edit and delete time cards"><i class="fa fa-question-circle"></i></span>
                            </td>
                            <td>
                                <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "attendance_permission_no",
                                    "name" => "attendance_permission",
                                    "value" => "",
                                    "class" => "attendance_permission toggle_specific",
                                        ), $attendance, ($attendance === "") ? true : false);
                                ?>
                                <label for="attendance_permission_no"><?php echo lang("no"); ?> </label>
                                </div>
                            </td>
                            <td>
                                <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "attendance_permission_all",
                                    "name" => "attendance_permission",
                                    "value" => "all",
                                    "class" => "attendance_permission toggle_specific",
                                        ), $attendance, ($attendance === "all") ? true : false);
                                ?>
                                <label for="attendance_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                </div>
                            </td>
                            <td>
                                <?php
                                echo form_radio(array(
                                    "id" => "attendance_permission_specific",
                                    "name" => "attendance_permission",
                                    "value" => "specific",
                                    "class" => "attendance_permission toggle_specific",
                                        ), $attendance, ($attendance === "specific") ? true : false);
                                ?>
                                <label for="attendance_permission_specific"><?php echo lang("yes_specific_members_or_teams") . " (" . lang("excluding_his_her_time_cards") . ")"; ?>:</label>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $attendance_specific; ?>" name="attendance_permission_specific" id="attendance_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />
                                </div>
                            </td>
                            </tr>
 -->
                            

                            <tr>
                                <td>
                                    <?php echo lang("can_manage_team_members_leave"); ?> <span class="help" data-toggle="tooltip" title="Assign, approve or reject leave applications"><i class="fa fa-question-circle"></i></span>
                                </td>
                                <td>
                                    <?php
                                        echo form_radio(array(
                                            "id" => "leave_permission_no",
                                            "name" => "leave_permission",
                                            "value" => "",
                                            "class" => "leave_permission toggle_specific",
                                                ), $leave, ($leave === "") ? true : false);
                                        ?>
                                        <label for="leave_permission_no"><?php echo lang("no"); ?></label>
                                </td>
                                <td>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "leave_permission_all",
                                        "name" => "leave_permission",
                                        "value" => "all",
                                        "class" => "leave_permission toggle_specific",
                                            ), $leave, ($leave === "all") ? true : false);
                                    ?>
                                    <label for="leave_permission_all"><?php echo lang("yes_all_members"); ?></label>
                                </td>
                                <td>
                                     <?php
                                    echo form_radio(array(
                                        "id" => "leave_permission_specific",
                                        "name" => "leave_permission",
                                        "value" => "specific",
                                        "class" => "leave_permission toggle_specific",
                                            ), $leave, ($leave === "specific") ? true : false);
                                    ?>
                                    <label for="leave_permission_specific"><?php echo lang("yes_specific_members_or_teams") . " (" . lang("excluding_his_her_leaves") . ")"; ?>:</label>
                                 <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $leave_specific; ?>" name="leave_permission_specific" id="leave_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                                </div>
                                </td>
                                </tr>

                            <tr>
                                <td>
                                <?php echo lang("can_access_final_settelment"); ?> ? <span class="help" data-toggle="tooltip" title="Assign, approve or reject leave applications"><i class="fa fa-question-circle"></i></span>
                                </td>
                                <td>
                                <?php
                                    echo form_radio(array(
                                        "id" => "final_settelment_no",
                                        "name" => "final_settelment",
                                        "value" => "",
                                            ), $final_settelment, ($final_settelment === "") ? true : false);
                                    ?>
                                    <label for="final_settelment"><?php echo lang("no"); ?> </label>
                                </td>
                                <td>
                                <?php
                                echo form_radio(array(
                                    "id" => "final_settelment_yes",
                                    "name" => "final_settelment",
                                    "value" => "all",
                                        ), $final_settelment, ($final_settelment === "all") ? true : false);
                                ?>
                                <label for="final_settelment_yes"><?php echo lang("yes"); ?></label>
                        
                                </td>
                            
                                </tr>

                            <tr>
                                <td>
                                    <?php echo lang("can_manage_job_info"); ?>
                                </td>
                                <td>
                                    <?php
                                        echo form_radio(array(
                                            "id" => "job_info_no",
                                            "name" => "job_info",
                                            "value" => "",
                                            "class" => "leave_permission toggle_specific",
                                                ), $job_info, ($job_info === "") ? true : false);
                                        ?>
                                        <label for="job_info_no"><?php echo lang("no"); ?></label>
                                </td>
                                <td>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "job_info_all",
                                        "name" => "job_info",
                                        "value" => "all",
                                        "class" => "leave_permission toggle_specific",
                                            ), $job_info, ($job_info === "all") ? true : false);
                                    ?>
                                    <label for="job_info_all"><?php echo lang("yes_all_members"); ?></label>
                                </td>
                                <!-- <td>
                                     <?php
                                    echo form_radio(array(
                                        "id" => "leave_permission_specific",
                                        "name" => "leave_permission",
                                        "value" => "specific",
                                        "class" => "leave_permission toggle_specific",
                                            ), $leave, ($leave === "specific") ? true : false);
                                    ?>
                                    <label for="leave_permission_specific"><?php echo lang("yes_specific_members_or_teams") . " (" . lang("excluding_his_her_leaves") . ")"; ?>:</label>
                                 <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $leave_specific; ?>" name="leave_permission_specific" id="leave_specific_dropdown" class="w100p validate-hidden"  data-rule-required="false" data-msg-required="<?php echo lang('field_required'); ?>" placeholder="<?php echo lang('choose_members_and_or_teams'); ?>"  />    
                                </div>
                                </td> -->
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div>
                                            <?php
                                            echo form_checkbox("can_delete_leave_application", "1", $can_delete_leave_application ? true : false, "id='can_delete_leave_application'");
                                            ?>
                                            <label for="can_delete_leave_application"><?php echo lang("can_delete_leave_application"); ?> <span class="help" data-toggle="tooltip" title="Can delete based on his/her access permission"><i class="fa fa-question-circle"></i></span></label>
                                        </div>
                                    </td>
                                </tr>
                            </tr>
                        </table>

                    <table>
                        <tr>
                            <td>
                                <?php echo lang("can_manage_announcements"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "announcement_no",
                                            "name" => "announcement_permission",
                                            "value" => "",
                                                ), $announcement, ($announcement === "") ? true : false);
                                        ?>
                                        <label for="announcement_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "announcement_yes",
                                            "name" => "announcement_permission",
                                            "value" => "all",
                                                ), $announcement, ($announcement === "all") ? true : false);
                                        ?>
                                        <label for="announcement_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <?php echo lang("can_manage_help_and_knowledge_base"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "help_no",
                                            "name" => "help_and_knowledge_base",
                                            "value" => "",
                                                ), $help_and_knowledge_base, ($help_and_knowledge_base === "") ? true : false);
                                        ?>
                                        <label for="help_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "help_yes",
                                            "name" => "help_and_knowledge_base",
                                            "value" => "all",
                                                ), $help_and_knowledge_base, ($help_and_knowledge_base === "all") ? true : false);
                                        ?>
                                        <label for="help_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <?php echo lang("disable_event_sharing"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "disable_event_sharing_no",
                                            "name" => "disable_event_sharing",
                                            "value" => "0",
                                                ), $disable_event_sharing, ($disable_event_sharing === "0") ? true : false);
                                        ?>
                                        <label for="disable_event_sharing_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "disable_event_sharing_yes",
                                            "name" => "disable_event_sharing",
                                            "value" => "1",
                                                ), $disable_event_sharing, ($disable_event_sharing === "1") ? true : false);
                                        ?>
                                        <label for="disable_event_sharing_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>

                        <!-- SALARY CHART -->
                        <tr>
                            <td>
                                <?php echo lang("can_view_salary_chart"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "can_view_salary_chart_no",
                                            "name" => "can_view_salary_chart",
                                            "value" => "0",
                                                ), $can_view_salary_chart, ($can_view_salary_chart === "0") ? true : false);
                                        ?>
                                        <label for="can_view_salary_chart_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "can_view_salary_chart_yes",
                                            "name" => "can_view_salary_chart",
                                            "value" => "1",
                                                ), $can_view_salary_chart, ($can_view_salary_chart === "1") ? true : false);
                                        ?>
                                        <label for="can_view_salary_chart_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- kjlkjfjalskdjfskd managament -->
                <button class="accordion"><?php echo lang("management"); ?></button>
                <div class="pane">

                    <table>

                        <tr>
                            <td>
                                <?php echo lang("can_manage_the_accounting_system"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "accounting_no",
                                            "name" => "accounting",
                                            "value" => "",
                                                ), $accounting, ($accounting === "") ? true : false);
                                        ?>
                                        <label for="accounting_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "accounting_yes",
                                            "name" => "accounting",
                                            "value" => "all",
                                                ), $accounting, ($accounting === "all") ? true : false);
                                        ?>
                                        <label for="accounting_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <?php echo lang("can_access_payroll"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "payroll_no",
                                            "name" => "payroll",
                                            "value" => "",
                                                ), $payroll, ($payroll === "") ? true : false);
                                        ?>
                                        <label for="payroll_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "payroll_yes",
                                            "name" => "payroll",
                                            "value" => "all",
                                                ), $payroll, ($payroll === "all") ? true : false);
                                        ?>
                                        <label for="payroll_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr> 

                       

                        <tr>
                            <td>
                                <?php echo lang("can_access_reports"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "reports_no",
                                            "name" => "reports",
                                            "value" => "",
                                                ), $reports, ($reports === "") ? true : false);
                                        ?>
                                        <label for="reports_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "reports_yes",
                                            "name" => "reports",
                                            "value" => "all",
                                                ), $reports, ($reports === "all") ? true : false);
                                        ?>
                                        <label for="reports_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <?php echo lang("can_access_eroom"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "eroom_no",
                                            "name" => "eroom",
                                            "value" => "",
                                                ), $eroom, ($eroom === "") ? true : false);
                                        ?>
                                        <label for="eroom_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "eroom_yes",
                                            "name" => "eroom",
                                            "value" => "all",
                                                ), $eroom, ($eroom === "all") ? true : false);
                                        ?>
                                        <label for="eroom_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <?php echo lang("can_access_logs"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "logs_no",
                                            "name" => "logs",
                                            "value" => "",
                                                ), $logs, ($logs === "") ? true : false);
                                        ?>
                                        <label for="logs_no"><?php echo lang("no"); ?> </label>
                                    </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "logs_yes",
                                            "name" => "logs",
                                            "value" => "all",
                                                ), $logs, ($logs === "all") ? true : false);
                                        ?>
                                        <label for="logs_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr>

                        <!-- <tr>
                            <td>
                                <?php echo lang("can_disable_event_sharing"); ?>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "disable_event_sharing_no",
                                            "name" => "disable_event_sharing",
                                            "value" => "",
                                                ), $disable_event_sharing, ($disable_event_sharing === "") ? true : false);
                                        ?>
                                        <label for="disable_event_sharing_no"><?php echo lang("no"); ?> </label>
                                </div>
                            </td>
                            <td>
                                <div>
                                        <?php
                                        echo form_radio(array(
                                            "id" => "disable_event_sharing_yes",
                                            "name" => "disable_event_sharing",
                                            "value" => "1",
                                                ), $disable_event_sharing, ($disable_event_sharing === "1") ? true : false);
                                        ?>
                                        <label for="logs_yes"><?php echo lang("yes"); ?></label>
                                    </div>
                            </td>
                        </tr> -->

                        <!-- <tr>
                            <td>
                            <?php echo lang("can_manage_announcements"); ?>
                            </td>
                            <td>
                                <div>
                                    <?php
                                     echo form_radio(array(
                                    "id" => "announcement_no",
                                    "name" => "announcement_permission",
                                    "value" => "0",
                                        ), $announcement, ($announcement === "0") ? true : false);
                                    ?>
                                <label for="announcement_no"><?php echo lang("no"); ?> </label>
                                </div>
                            </td>
                            <td>
                                <div>
                                <?php
                                    echo form_radio(array(
                                        "id" => "announcement_yes",
                                        "name" => "announcement_permission",
                                        "value" => "1",
                                            ), $announcement, ($announcement === "1") ? true : false);
                                    ?>
                                    <label for="announcement_yes"><?php echo lang("yes"); ?></label>
                                </div>
                            </td>
                        </tr> -->


                    </table>
                </div>

                <!-- kjlkjfjalskdjfskd approvals -->
                <button class="accordion"><?php echo lang("approvals"); ?></button>
                <div class="pane">
                    <table>
                        <tr>
                        <td>
                            <?php echo lang("can_approve_expenses"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "expenses_no",
                                        "name" => "expenses",
                                        "value" => "",
                                            ), $expenses, ($expenses === "") ? true : false);
                                    ?>
                                    <label for="expenses_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "expenses_yes",
                                        "name" => "expenses",
                                        "value" => "all",
                                            ), $expenses, ($expenses === "all") ? true : false);
                                    ?>
                                    <label for="expenses_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>

                        <!-- <tr>
                        <td>
                            <?php echo lang("can_approve_internal_transactions"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "internal_transactions_no",
                                        "name" => "internal_transactions",
                                        "value" => "",
                                            ), $internal_transactions, ($internal_transactions === "") ? true : false);
                                    ?>
                                    <label for="internal_transactions_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "internal_transactions_yes",
                                        "name" => "internal_transactions",
                                        "value" => "all",
                                            ), $internal_transactions, ($internal_transactions === "all") ? true : false);
                                    ?>
                                    <label for="internal_transactions_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr> -->

                        <tr>
                        <td>
                            <?php echo lang("can_approve_estimates"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimates_no",
                                        "name" => "estimates",
                                        "value" => "",
                                            ), $estimates, ($estimates === "") ? true : false);
                                    ?>
                                    <label for="estimates_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "estimates_yes",
                                        "name" => "estimates",
                                        "value" => "all",
                                            ), $estimates, ($estimates === "all") ? true : false);
                                    ?>
                                    <label for="estimates_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>

                        <tr>
                        <td>
                            <?php echo lang("can_approve_and_process_invoices"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoices_no",
                                        "name" => "invoices",
                                        "value" => "",
                                            ), $invoices, ($invoices === "") ? true : false);
                                    ?>
                                    <label for="invoices_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoices_yes",
                                        "name" => "invoices",
                                        "value" => "all",
                                            ), $invoices, ($invoices === "all") ? true : false);
                                    ?>
                                    <label for="invoices_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>

                        <tr>
                        <td>
                            <?php echo lang("can_approve_invoice_payments"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_payments_no",
                                        "name" => "invoice_payments",
                                        "value" => "",
                                            ), $invoice_payments, ($invoice_payments === "") ? true : false);
                                    ?>
                                    <label for="invoice_payments_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "invoice_payments_yes",
                                        "name" => "invoice_payments",
                                        "value" => "all",
                                            ), $invoice_payments, ($invoice_payments === "all") ? true : false);
                                    ?>
                                    <label for="invoice_payments_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>
                 
                        <tr>
                        <td>
                            <?php echo lang("can_approve_delivery_notes"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "delivery_notes_no",
                                        "name" => "delivery_notes",
                                        "value" => "",
                                            ), $delivery_notes, ($delivery_notes === "") ? true : false);
                                    ?>
                                    <label for="delivery_notes_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "delivery_notes_yes",
                                        "name" => "delivery_notes",
                                        "value" => "all",
                                            ), $delivery_notes, ($delivery_notes === "all") ? true : false);
                                    ?>
                                    <label for="delivery_notes_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>

                        <tr>
                        <td>
                            <?php echo lang("can_approve_purchase_orders"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "purchase_orders_no",
                                        "name" => "purchase_orders",
                                        "value" => "",
                                            ), $purchase_orders, ($purchase_orders === "") ? true : false);
                                    ?>
                                    <label for="purchase_orders_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "purchase_orders_yes",
                                        "name" => "purchase_orders",
                                        "value" => "all",
                                            ), $purchase_orders, ($purchase_orders === "all") ? true : false);
                                    ?>
                                    <label for="purchase_orders_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>

                        <tr>
                        <td>
                            <?php echo lang("can_approve_purchase_order_payments"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "purchase_order_payments_no",
                                        "name" => "purchase_order_payments",
                                        "value" => "",
                                            ), $purchase_order_payments, ($purchase_order_payments === "") ? true : false);
                                    ?>
                                    <label for="purchase_order_payments_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "purchase_order_payments_yes",
                                        "name" => "purchase_order_payments",
                                        "value" => "all",
                                            ), $purchase_order_payments, ($purchase_order_payments === "all") ? true : false);
                                    ?>
                                    <label for="purchase_order_payments_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>
                        <!-- <tr>
                            <td> <?php echo lang("can_approve_budgeting"); ?>?</td>
                            <td>
                            <?php
                            echo form_radio(array(
                                "id" => "approve_budgeting_no",
                                "name" => "approve_budgeting",
                                "value" => "",
                                    ), $approve_budgeting, ($approve_budgeting === "") ? true : false);
                            ?>
                            <label for="approve_budgeting_no"><?php echo lang("no"); ?> </label>
                        </td> 
                            <td>
                            <?php
                            echo form_radio(array(
                                "id" => "approve_budgeting_yes",
                                "name" => "approve_budgeting",
                                "value" => "all",
                                    ), $approve_budgeting, ($approve_budgeting === "all") ? true : false);
                            ?>
                            <label for="approve_budgeting_yes"><?php echo lang("yes"); ?></label>
                        </td> 
                        </tr> -->
                        <!-- <tr>
                        <td>
                            <?php echo lang("can_approve_payroll"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "payrolls_no",
                                        "name" => "payrolls",
                                        "value" => "",
                                            ), $payrolls, ($payrolls === "") ? true : false);
                                    ?>
                                    <label for="payrolls_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "payrolls_yes",
                                        "name" => "payrolls",
                                        "value" => "all",
                                            ), $payrolls, ($payrolls === "all") ? true : false);
                                    ?>
                                    <label for="payrolls_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr> -->

                        <!-- <tr>
                        <td>
                            <?php echo lang("can_approve_dispatches"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "dispatches_no",
                                        "name" => "dispatches",
                                        "value" => "",
                                            ), $dispatches, ($dispatches === "") ? true : false);
                                    ?>
                                    <label for="dispatches_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "dispatches_yes",
                                        "name" => "dispatches",
                                        "value" => "all",
                                            ), $dispatches, ($dispatches === "all") ? true : false);
                                    ?>
                                    <label for="dispatches_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr>

                        <tr>
                        <td>
                            <?php echo lang("can_approve_shipments"); ?>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "shipments_no",
                                        "name" => "shipments",
                                        "value" => "",
                                            ), $shipments, ($shipments === "") ? true : false);
                                    ?>
                                    <label for="shipments_no"><?php echo lang("no"); ?> </label>
                                </div>
                        </td>
                        <td>
                            <div>
                                    <?php
                                    echo form_radio(array(
                                        "id" => "shipments_yes",
                                        "name" => "shipments",
                                        "value" => "all",
                                            ), $shipments, ($shipments === "all") ? true : false);
                                    ?>
                                    <label for="shipments_yes"><?php echo lang("yes"); ?></label>
                                </div>
                        </td>
                        </tr> --> 
                    </table>
                </div>

            </div>   
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary mr10"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#permissions-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $(function() {
            enable_cb1();
            $(".can_access_contacts").change(enable_cb1);
        });

        function enable_cb1() {
            if ($('.can_access_contacts').is(':unchecked')){
                $('.contactGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.contactGrp').removeAttr('disabled');
            }
        }
        $(function() {
            enable_cb2();
            $(".can_access_clients").change(enable_cb2);
        });

        function enable_cb2() {
            if ($('.can_access_clients').is(':unchecked')){
                $('.clientGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.clientGrp').removeAttr('disabled');
            }
        }
        $(function() {
            enable_cb3();
            $(".can_access_invoices").change(enable_cb3);
        });

        function enable_cb3() {
            if ($('.can_access_invoices').is(':unchecked')){
                $('.invoiceGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.invoiceGrp').removeAttr('disabled');
            }
        }
        $(function() {
            enable_cb4();
            $(".can_access_estimates").change(enable_cb4);
        });

        function enable_cb4() {
            if ($('.can_access_estimates').is(':unchecked')){
                $('.estimateGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.estimateGrp').removeAttr('disabled');
            }
        }
        $(function() {
            enable_cb5();
            $(".can_access_expenses").change(enable_cb5);
        });

        function enable_cb5() {
            if ($('.can_access_expenses').is(':unchecked')){
                $('.expensesGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.expensesGrp').removeAttr('disabled');
            }
        }
        $(function() {
            enable_cb6();
            $(".can_access_suppliers").change(enable_cb6);
        });

        function enable_cb6() {
            if ($('.can_access_suppliers').is(':unchecked')){
                $('.supplierGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.supplierGrp').removeAttr('disabled');
            }
        }
        $(function() {
            enable_cb7();
            $(".can_access_purchase_orders").change(enable_cb7);
        });

        function enable_cb7() {
            if ($('.can_access_purchase_orders').is(':unchecked')){
                $('.purchase_orderGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.purchase_orderGrp').removeAttr('disabled');
            }
        }
        /*$(function() {
            enable_cb8();
            $(".can_access_dispatches").change(enable_cb8);
        });

        function enable_cb8() {
            if ($('.can_access_dispatches').is(':unchecked')){
                $('.dispatcheGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.dispatcheGrp').removeAttr('disabled');
            }
        }*/

        $(function() {
            enable_cb9();
            $(".can_access_items").change(enable_cb9);
        });

        function enable_cb9() {
            if ($('.can_access_items').is(':unchecked')){
                $('.itemGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.itemGrp').removeAttr('disabled');
            }
        }
        $(function() {
            enable_cb9();
            $(".can_access_items_category").change(enable_cb9);
        });

        function enable_cb9() {
            if ($('.can_access_items_category').is(':unchecked')){
                $('.itemCategoryGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.itemCategoryGrp').removeAttr('disabled');
            }
        }

        /*$(function() {
            enable_cb10();
            $(".can_access_shipments").change(enable_cb10);
        });

        function enable_cb10() {
            if ($('.can_access_shipments').is(':unchecked')){
                $('.shipmentGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.shipmentGrp').removeAttr('disabled');
            }
        }*/

        /*$(function() {
            enable_cb11();
            $(".can_access_smss").change(enable_cb11);
        });

        function enable_cb11() {
            if ($('.can_access_smss').is(':unchecked')){
                $('.smsGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.smsGrp').removeAttr('disabled');
            }
        }

        $(function() {
            enable_cb12();
            $(".can_access_emails").change(enable_cb12);
        });

        function enable_cb12() {
            if ($('.can_access_emails').is(':unchecked')){
                $('.emailGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.emailGrp').removeAttr('disabled');
            }
        }*/
        $(function() {
            enable_cb13();
            $(".can_access_delivery_notes").change(enable_cb13);
        });

        function enable_cb13() {
            if ($('.can_access_delivery_notes').is(':unchecked')){
                $('.delivery_noteGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.delivery_noteGrp').removeAttr('disabled');
            }
        }

        /*$(function() {
            enable_cb14();
            $(".can_access_assets").change(enable_cb14);
        });

        function enable_cb14() {
            if ($('.can_access_assets').is(':unchecked')){
                $('.assetGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.assetGrp').removeAttr('disabled');
            }
        }

        $(function() {
            enable_cb15();
            $(".can_access_fleets").change(enable_cb15);
        });

        function enable_cb15() {
            if ($('.can_access_fleets').is(':unchecked')){
                $('.fleetGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.fleetGrp').removeAttr('disabled');
            }
        }*/

        $(function() {
            enable_cb16();
            $(".can_access_internal_tasks").change(enable_cb16);
        });

        function enable_cb16() {
            if ($('.can_access_internal_tasks').is(':unchecked')){
                $('.internal_taskGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.internal_taskGrp').removeAttr('disabled');
            }
        }

        $(function() {
            enable_cb17();
            $(".can_access_invoice_payments").change(enable_cb17);
        });

        function enable_cb17() {
            if ($('.can_access_invoice_payments').is(':unchecked')){
                $('.invoice_paymentGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.invoice_paymentGrp').removeAttr('disabled');
            }
        }

        $(function() {
            enable_cb18();
            $(".can_access_purchase_order_payments").change(enable_cb18);
        });

        function enable_cb18() {
            if ($('.can_access_purchase_order_payments').is(':unchecked')){
                $('.purchase_order_paymentGrp').attr('disabled',true).removeAttr('checked');
            } else {
                $('.purchase_order_paymentGrp').removeAttr('disabled');
            }
        }

        $("table").addClass('table table-hover table-bordered');
        $("table").css('overflow-y:scroll;');

        $("#leave_specific_dropdown, #attendance_specific_dropdown, #timesheet_manage_permission_specific_dropdown,#leads_manage_permission_specific_dropdown,  #team_member_update_permission_specific_dropdown,       #contact_manage_permission_specific_dropdown,#client_manage_permission_specific_dropdown,#invoice_manage_permission_specific_dropdown,#invoice_return_manage_permission_specific_dropdown,#estimate_manage_permission_specific_dropdown, #delivery_note_manage_permission_specific_dropdown,#expense_manage_permission_specific_dropdown,#supplier_manage_permission_specific_dropdown,#purchase_order_manage_permission_specific_dropdown,#dispatch_manage_permission_specific_dropdown,#item_manage_permission_specific_dropdown ,#shipment_manage_permission_specific_dropdown").select2({
            multiple: true,
            formatResult: teamAndMemberSelect2Format,
            formatSelection: teamAndMemberSelect2Format,
            data: <?php echo ($members_and_teams_dropdown); ?>
        });

        $("#ticket_types_specific_dropdown").select2({
            multiple: true,
            data: <?php echo ($ticket_types_dropdown); ?>
        });

        $('[data-toggle="tooltip"]').tooltip();

        $(".toggle_specific").click(function () {
            toggle_specific_dropdown();
        });

        toggle_specific_dropdown();
        function toggle_specific_dropdown() {
            var selectors = [".leave_permission", ".attendance_permission", ".timesheet_manage_permission", ".team_member_update_permission", ".ticket_permission",      ".contact_manage_permission", ".client_manage_permission", ".project_manage_permission", ".invoice_manage_permission", ".estimate_manage_permission", ".expense_manage_permission", ".delivery_note_manage_permission", ".supplier_manage_permission", ".purchase_order_manage_permission", ".dispatch_manage_permission", ".item_manage_permission" ,".email_manage_permission" , ".sms_manage_permission", ".shipment_manage_permission"];
            $.each(selectors, function (index, element) {
                var $element = $(element + ":checked");
                if ($element.val() === "specific") {
                    $element.closest("tr").find(".specific_dropdown").show().find("input").addClass("validate-hidden");
                } else {
                    //console.log($element.closest("tr").find(".specific_dropdown"));
                    $(element).closest("tr").find(".specific_dropdown").hide().find("input").removeClass("validate-hidden");
                }
            });

        }

        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
          acc[i].addEventListener("click", function(e) {
            this.classList.toggle("active");
            e.preventDefault();
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
              panel.style.maxHeight = null;
            } else {
              panel.style.maxHeight = panel.scrollHeight + "px";
            } 
          });
        }
    });
</script>    