<?php
$location = get_setting('user_' . $this->login_user->id . '_location');
$lat = $location ? strtok($location, ",") : -78.99472450605815 ;
$lng = $location ? substr($location, strpos($location, ",") + 1) : 47.25444498982938;
?>

<?php 
  $access = get_setting("module_attendance");
  if($access == "1") { ?>
    <div id="js-init-clock-icon" class="init-clock-icon">
        <span id="js-clock-min-icon" class="chat-min-icon"><i class='fa fa-clock-o font-20'></i></span>
    </div>
<?php } ?>

<?php $clock_status = $this->Attendance_model->current_clock_in_record($this->login_user->id); ?>

<script type="text/javascript">
    $(document).ready(function () {

        /*setTimeout(function() {
            location.reload();
        }, 900000);*/

        $("#js-init-clock-icon").hide();

        var current_location;
        if (navigator.geolocation) {
            //navigator.geolocation.getCurrentPosition(showPosition);
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
             $("#js-init-clock-icon").show();
          } 
       
        }

        var clock_in = "<?php echo isset($clock_status->id) ?>";

        if(clock_in)
           {
              $("#js-init-clock-icon").addClass("highlightCI");
           }
        else
           {
              $("#js-init-clock-icon").removeClass("highlightCI");
           }

        $Icon = $("#js-init-clock-icon");

        $Icon.click(function () {
           
           if(clock_in)
           {
            appLoader.show({css: "z-index:10000;"});
            $.ajax({
                  type: "POST",
                  url: '<?php echo_uri("attendance/clock_in_or_out") ?>',
                  success: function() {
                     window.location.reload();
                     setTimeout(function() {
                        appLoader.hide();
                    }, 1000);
                  }
                });
           }
           else
           {
              appLoader.show({css: "z-index:10000;"});
              $.ajax({
                  type: "POST",
                  url: '<?php echo_uri("attendance/clock_in_or_out") ?>',
                  success: function() {
                     window.location.reload();
                     setTimeout(function() {
                        appLoader.hide();
                    }, 1000);
                  }
                });  
            }
        });
    });
</script>
