<div class="tab-content">
    <?php
    echo form_open(get_uri("team_members/save_attendance_settings/" . $user_id), array("id" => "attendance_settings-form", "class" => "general-form dashed-row white", "role" => "form"));
    ?>
    <input type="hidden" id ="location" name="location" value="<?php echo(get_setting('user_' . $user_id . '_location')); ?>" />
    <div class="panel">
        <div class="panel-default panel-heading">
            <h4><?php echo lang('attendance_settings'); ?></h4>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label for="password" class=" col-md-2"><?php echo lang('password'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_password(array(
                        "id" => "password",
                        "name" => "password",
                        "class" => "form-control",
                        "placeholder" => lang('password'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="allowed_distance" class=" col-md-2"><?php echo ('Allowed Distance'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo form_input(array(
                        "id" => "allowed_distance",
                        "name" => "allowed_distance",
                        "value" => get_setting('user_' . $user_id . '_allowed_distance') ? get_setting('user_' . $user_id . '_allowed_distance')  : "",
                        "class" => "form-control",
                        "placeholder" => ('In Meters'),
                        "data-rule-required" => true,
                        "data-msg-required" => lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="status" class=" col-md-2"><?php echo ('Device Registeration Status'); ?></label>
                <div class=" col-md-10">
                    <?php
                    echo "<span class='label $status_label large'>" . lang($status) . "</span>"
                    ?>
                </div>
            </div>

            <label for="status" class=" col-md-10"><?php echo ('Work Location'); ?></label>
            <div id="googleMap" style="width:100%;height:400px;"> 
            </div>

        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#attendance_settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                window.location.href = "<?php echo get_uri("team_members/view/" . $user_id); ?>" + "/attendance_settings";
            }
        });

        $("#attendance_settings-form .select2").select2();    
    });

        var markers = [];    

        function myMap() {
            var LongLat= new google.maps.LatLng(23.587340,58.356314);
            var mapProp= {
              center: LongLat,
              zoom:8,
            };

            var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
          
            var model = "<?php echo(get_setting('user_' . $user_id . '_location')); ?>";

            var lat = ("<?php echo(get_setting('user_' . $user_id . '_location')); ?>" == "") ? 23.587340 :parseFloat('<?php echo strtok(get_setting('user_' . $user_id . '_location'), ","); ?>');
            var lng = ("<?php echo(get_setting('user_' . $user_id . '_location')); ?>" == "") ? 58.356314 :parseFloat('<?php echo substr(get_setting('user_' . $user_id . '_location'), strpos(get_setting('user_' . $user_id . '_location'), ",") + 1); ?>');

            if (model != ""){        
                var mark = new google.maps.Marker({
                    position: {lat: lat, lng: lng},
                    title: 'saved location',
                    map: map
                });

                var urls = lat+','+lng;
                var mainURls = 'https://www.google.com/maps/search/?api=1&query='+urls;
                var contentstrings = '<a href="'+mainURls+'" target="_blank">View Saved Location on Google Maps</a>';
                var infowindow = new google.maps.InfoWindow({
                        content: contentstrings
                    });

                infowindow.open(map,mark);
                markers.push(mark);
            }
            
            google.maps.event.addListener(map, 'dblclick', function(event) {
                if (markers[0]){
                  markers[0].setMap(null);  
                  markers.pop();
                }
                placeMarker(map, event.latLng); 
            });


            function placeMarker(map, location) {
                   var marker = new google.maps.Marker({
                        position: location,
                        map: map
                    
                   });
                    
                   markers.push(marker);

                   var url = location.lat()+','+location.lng();
                   var mainURl = 'https://www.google.com/maps/search/?api=1&query='+url;
                   var contentstring = '<br><a href="'+mainURl+'" target="_blank" >View on Google Maps</a>';
                    
                   var infowindow = new google.maps.InfoWindow({
                        content: 'Latitude: ' + location.lat() +
                        '<br>Longitude: ' + location.lng() + contentstring
                   });
                  
                   infowindow.open(map,marker);
                  
                   $('#location').val(location.lat() + ',' + location.lng());
            }

        }

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPx5xVOlFvc7NJfH3Co4DeN-4ETLWkaE8&callback=myMap"></script>   