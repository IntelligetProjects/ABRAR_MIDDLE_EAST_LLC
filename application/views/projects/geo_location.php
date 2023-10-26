
<div class="panel view-container">
        <?php echo form_open(get_uri("projects/save_geo_location"), array("id" => "project-form", "class" => "general-form", "role" => "form")); ?>

    <div class="panel">

        <div class="tab-title clearfix">
            <h4><?php echo lang('geo_location'); ?></h4>
        </div>

        <div class="panel-body">
            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
            <input type="hidden" id ="geo_location" name="geo_location" value="" />
        
            <!-- map -->
            <div class="form-group">
                    <div id="googleMap" style="width:100%;height:200px;"> 
            </div>

        </div>

        <div class="panel-footer clearfix">
            <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div> 


<script type="text/javascript">
    $(document).ready(function () {

        $("#project-form").appForm({
            onSuccess: function (result) {
                location.reload(); 
            }
        });
        
    });
        var markers = [];    

        function myMap() {
            var LongLat= new google.maps.LatLng(23.587340,58.356314);
            var mapProp= {
              center: LongLat,
              zoom:8,
            };

            var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
          
            var model = "<?php echo($model_info->geo_location); ?>";

            var lat = ("<?php echo($model_info->geo_location); ?>" == "") ? 23.587340 :parseFloat('<?php echo strtok($model_info->geo_location, ","); ?>');
            var lng = ("<?php echo($model_info->geo_location); ?>" == "") ? 58.356314 :parseFloat('<?php echo substr($model_info->geo_location, strpos($model_info->geo_location, ",") + 1); ?>');

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
                  
                   $('#geo_location').val(location.lat() + ',' + location.lng());
            }

        }
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBGYXSs9oeRuaYYphG7cIIt_kPmdQj6oqI&callback=myMap"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvBjjdibdqlMnbRHREn706SWLpfVZPJR4&callback=myMap"></script>