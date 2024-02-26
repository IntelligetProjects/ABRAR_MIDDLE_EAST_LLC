<div id="page-content" class="p20 clearfix">

    <?php
    if (count($dashboards)) {
        $this->load->view("dashboards/dashboard_header");
    }

    announcements_alert_widget();
    ?>

    <div class="row">
        <?php
        $widget_column = "3"; //default bootstrap column class
        $total_hidden = 0;

        if (!$show_attendance) {
            $total_hidden += 1;
        }

        if (!$show_event) {
            $total_hidden += 1;
        }

        if (!$show_timeline) {
            $total_hidden += 1;
        }

        //set bootstrap class for column
        if ($total_hidden == 1) {
            $widget_column = "4";
        } else if ($total_hidden == 2) {
            $widget_column = "6";
        } else if ($total_hidden == 3) {
            $widget_column = "12";
        }
        ?>

        <div class="col-md-8 widget-container">
            <?php
            if ($show_invoice_statistics && $this->login_user->is_admin) {
                invoice_statistics_widget();
            }
            ?>
        </div>
        <div class="col-md-4 widget-container">
            <?php
            if ($show_income_vs_expenses && $this->login_user->is_admin) {
                income_vs_expenses_widget();
            }
            ?>
        </div>


        <?php if ($show_attendance) { ?>
            <div class="col-md-<?php echo $widget_column; ?> col-sm-6 widget-container">
                <?php
                clock_widget();
                ?>
            </div>
        <?php } ?>

        <div class="col-md-<?php echo $widget_column; ?> col-sm-6  widget-container">
            <?php
            my_open_tasks_widget();
            ?> 
        </div>

        <?php if ($show_event) { ?>
            <div class="col-md-<?php echo $widget_column; ?> col-sm-6  widget-container">
                <?php
                events_today_widget();
                ?> 
            </div>
        <?php } ?>

        <?php if ($show_timeline) { ?>
            <div class="col-md-<?php echo $widget_column; ?> col-sm-6  widget-container">
                <?php
                new_posts_widget();
                ?>  
            </div>
        <?php } ?>

    </div>

    <div class="row">
        <div class="col-md-8 widget-container">
            <?php sticky_note_widget(); ?>
        </div>
        <div class="col-md-4 widget-container">
        <canvas id="myChart" width="200" height="200"></canvas>
        </div>
        <br><br>
    </div>
    <?php 
    $sql="SELECT nationality ,COUNT(nationality) as num FROM ".$this->db->dbprefix('users')." WHERE deleted =0 group by nationality";
    $query=$this->db->query($sql);
    $da=$query->result_array();
    
    // var_dump($na);die();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" integrity="sha256-+8RZJua0aEWg+QVVKg4LEzEEm/8RFez5Tb4JBNiV5xA=" crossorigin="anonymous"></script>
    <script>
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [<?php
         foreach($da as $d){
            if(isset($d['nationality'])){
             echo '"'.$d['nationality'].'",';
            }
        }?>],
        datasets: [{
    label: 'My First Dataset',
    data: [<?php
         foreach($da as $d){
        echo $d['num'].',';
        }?>],
    backgroundColor: [<?php
    $c=colors(count($da));
    for($i=0;$i<count($c);$i++){
        echo '"'.$c[$i].'",';
    }
    ?>],
    hoverOffset: 4
  }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
 <?php function colors($num){
$col=['rgb(255, 99, 132)','rgb(54, 162, 235)', 'rgb(255, 205, 86)','rgb(255,99,71)','rgb(124,252,0)','rgb(0,191,255)','rgb(255,192,203)','rgb(105,105,105)','rgb(138,43,226)'];
$colors=[];
for($x=0;$x<$num;$x++){
$colors[$x]=$col[$x];
}
// var_dump($colors);die();
return $colors;
 }
 ?>
    <!-- <div class="row">
        <div class="col-md-5">

            <?php if ($show_projects_count || $show_clock_status || $show_total_hours_worked || $show_total_project_hours) { ?>
                <div class="row">
                    <div class="col-md-12 mb20 text-center">
                        <div class="bg-white">
                            <?php
                            if ($show_projects_count) {
                                count_project_status_widget();
                            }

                            if ($show_clock_status) {
                                count_clock_status_widget();
                            } else {
                                count_total_time_widget();
                            }
                            ?> 
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    
                        if ($this->login_user->is_admin) {
                            project_timesheet_statistics_widget("all_timesheet_statistics");
                        } else {
                            project_timesheet_statistics_widget("my_timesheet_statistics");
                        }
                    ?> 
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 mb15">
                    <?php
                    if ($show_ticket_status) {
                        ticket_status_widget();
                    } else if ($show_attendance) {
                        timecard_statistics_widget();
                    }
                    ?>                        
                </div>
            </div>

        </div>

        <div class="col-md-4 widget-container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-clock-o"></i>&nbsp;  <?php echo lang("project_timeline"); ?>
                </div>
                <div id="project-timeline-container">
                    <div class="panel-body"> 
                        <?php
                        activity_logs_widget(array("log_for" => "project", "limit" => 10));
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3 widget-container">
            <?php
            
               my_task_stataus_widget();
            ?>
        </div>

        <?php if ($show_event) { ?>
            <div class="col-md-3 widget-container">
                <?php events_widget(); ?>
            </div>
        <?php } ?>

        <div class="col-md-3 widget-container">
            <?php sticky_note_widget(); ?>
        </div>
    </div> -->

</div>

<!--password  Modal -->
<div style="margin-top: 100px;" class="modal fade" id="pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Password Change</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      Kindly change your password
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div style="margin-top: 100px;" class="modal fade" id="up" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Please Upload Your CR Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       To upload CR document please go to your profile setting 
      </div>
    </div>
  </div>
</div>

<?php
         $url = base_url();
         $urlParts = explode('/', str_ireplace(array('http://', 'https://'), '', $url));
         $folder_domain= $urlParts[1];
         ?>
<script type="text/javascript">
    $(document).ready(function () {
        initScrollbar('#project-timeline-container', {
            setHeight: 955
        });


        // password modal 
if(localStorage.getItem('pass')!=0){
    $('#pass').modal('show');
    localStorage.setItem('pass',0);

}
       

        //update dashboard link
        $(".dashboard-menu, .dashboard-image").closest("a").attr("href", window.location.href);


        // $.post( "https://teamway.omantel.om/tasgeelxxx/index.php/upload/check_upload", { product_domain: "<?php echo  $folder_domain ?>" }, function( data ) {
        // console.log( data.flag ); 
        // if(data.flag){
        //     $('#up').modal('show')
        // }
        // }, "json");

        // $.post( "<?php echo_uri('items/check_stock'); ?>", { }, function( data ) {
        // console.log( data ); 
        // }, "json");

    });
</script>    


<script type="text/javascript">
    $(document).ready(function () {
        initScrollbar('#project-timeline-container', {
            setHeight: 955
        });

        //update dashboard link
        $(".dashboard-menu, .dashboard-image").closest("a").attr("href", window.location.href);

    });
</script>    

