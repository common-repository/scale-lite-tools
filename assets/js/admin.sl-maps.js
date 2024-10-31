function slt_gmaps_init() {

  // map admin page
  if(document.getElementById("preview_map_container")){
    var sl_preview_map = function() {
      var geocoder = new google.maps.Geocoder();
      // create map element
      var mapEl = document.getElementById("preview_map_container");
      var map = new google.maps.Map(mapEl, {
        zoom: 12,
        scrollwheel: false
      });
      function createMarker(myLocation) {
        var marker = new google.maps.Marker({
          position: myLocation,
          map: map
        });
      }
      function codeAddress(callback) {
        var address = document.getElementById('slt_meta-center-map-location').value;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == 'OK') {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            document.getElementById("slt_meta-latitude").value = results[0].geometry.location.lat();
            document.getElementById("slt_meta-longitude").value = results[0].geometry.location.lng();
            callback(results[0].geometry.location,map);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
      codeAddress(function(myLocation) { createMarker(myLocation, map);});
    };
    sl_preview_map();

    var markerPreviewHandle = document.getElementById("slt-map-preview");
    markerPreviewHandle.addEventListener("click", function(e) {
      sl_preview_map();
    }, false);
  }

  // marker admin page
  if(document.getElementById("preview_marker_container")){
    var sl_preview_map = function() {
      var geocoder = new google.maps.Geocoder();
      // create map element
      var mapEl = document.getElementById("preview_marker_container");
      var map = new google.maps.Map(mapEl, {
        zoom: 12,
        scrollwheel: false
      });
      function createMarker(myLocation) {
        var marker = new google.maps.Marker({
          position: myLocation,
          map: map
        });
        var infowindow = new google.maps.InfoWindow({
          content: '<h4>'+document.getElementById("slt_meta-title").value+'</h4>'+
            '<p>'+document.getElementById("slt_meta-description").value+'</p>',
          maxWidth: 250
        });
        infowindow.open(map, marker);
      }
      function codeAddress(callback) {
        var address = document.getElementById('slt_meta-location-address').value;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == 'OK') {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            document.getElementById("slt_meta-latitude").value = results[0].geometry.location.lat();
            document.getElementById("slt_meta-longitude").value = results[0].geometry.location.lng();
            callback(results[0].geometry.location,map);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
      codeAddress(function(myLocation) { createMarker(myLocation, map);});
    };
    sl_preview_map();

    var markerPreviewHandle = document.getElementById("slt-marker-preview");
    markerPreviewHandle.addEventListener("click", function(e) {
      sl_preview_map();
    }, false);
  }

}
