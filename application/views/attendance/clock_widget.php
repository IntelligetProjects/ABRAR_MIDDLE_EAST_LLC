<div id="js-clock-in-out" class="panel <?php echo (isset($clock_status->id)) ? 'panel-info' : 'panel-coral'; ?>">
    <div class="panel-body ">
        <div class="widget-icon">
            <i class="fa fa-clock-o"></i>
        </div>
        <div class="widget-details">
            <?php
            if (isset($clock_status->id)) {
                $in_time = format_to_time($clock_status->in_time);
                $in_datetime = format_to_datetime($clock_status->in_time);
                echo "<div class='mb15' title='$in_datetime'>" . lang('clock_started_at') . " : $in_time</div>";
              
                echo "<div id='button'>" .modal_anchor(get_uri("attendance/note_modal_form"), "<i class='fa fa-sign-out'></i> " . lang('clock_out'), array("class" => "btn btn-default no-border", "title" => lang('clock_out'), "id"=>"timecard-clock-out", "data-post-id" => $clock_status->id, "data-post-clock_out"=>1)) . "</div>";
            } else {
                echo "<div class='mb15'>" . lang('you_are_currently_clocked_out') . "</div>";
                echo "<div id='button'>" . ajax_anchor(get_uri("attendance/log_time"), "<i class='fa fa-sign-in'></i> " . lang('clock_in'), array("class" => "btn btn-default no-border", "title" => lang('clock_in'), "data-inline-loader" => "1", "data-closest-target" => "#js-clock-in-out", "data-post-check" => "#attendance_check")) . "</div>";
            }
            ?>
        </div>
    </div>
</div>

<?php
$location = get_setting('user_' . $this->login_user->id . '_location');
$lat = $location ? strtok($location, ",") : -78.99472450605815 ;
$lng = $location ? substr($location, strpos($location, ",") + 1) : 47.25444498982938;
?>

<script type="text/javascript">
    $(document).ready(function () {

        $("#button").hide();

        var current_location;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            alert ("Geolocation is not supported by this browser.");
        }

        function showPosition(position) {
          current_location = "Latitude: " + position.coords.latitude + 
          "<br>Longitude: " + position.coords.longitude;

          var lat1 = position.coords.latitude.toString();
          var lng1 = position.coords.longitude.toString();

          var lat2 = parseFloat('<?= $lat; ?>');
          var lng2 = parseFloat('<?= $lng; ?>');

          var R = 6371; // km (change this constant to get miles)
          var dLat = (lat1-lat2) * Math.PI / 180;
          var dLon = (lng1-lng2) * Math.PI / 180;
          var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
          var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
          var d = R * c;
          var actual_distance = Math.round(d*1000);

          var register = "<?php echo isset($_COOKIE['Device']) ?>";

          var allowed_distance =  Number("<?= get_setting('user_' . $this->login_user->id . '_allowed_distance')?>");

          var enable_attendance = "<?php echo get_setting('enable_attendance'); ?>";

          if((register && allowed_distance > actual_distance) || enable_attendance != 1) {
             $("#button").show();
          } 
       
        }
    });
</script>