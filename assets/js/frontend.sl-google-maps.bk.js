// google maps render frontend map
var slt_gmaps_init = function() {
  "use strict";

  if (typeof scaleLiteMapsBuffer != 'undefined' &&
    typeof scaleLiteMarkersBuffer != 'undefined') {

    var geocoder = new google.maps.Geocoder();
    for (var prop in scaleLiteMapsBuffer) {
      var mapObject = scaleLiteMapsBuffer[prop];
      // get lat and long from map meta fields
      var myLocation = {
        lat: parseFloat(mapObject.map_latitude),
        lng: parseFloat(mapObject.map_longitude)
      };

      // create map element
      var mapEl = document.createElement('div'); mapEl.setAttribute("id", "sl-map-" + mapObject.html_id);
      mapObject.width = mapObject.width ? mapObject.width + "px" : '100%';
      // append map element
      if (document.contains(document.getElementById(mapObject.html_id))) {
        document.getElementById(mapObject.html_id).appendChild(mapEl).setAttribute(
          "style", "width:" + mapObject.width + ";min-height:" + mapObject.height +
          "px;");
      }
      var map = new google.maps.Map(mapEl, {
        zoom: parseInt(mapObject.zoom),
        center: myLocation,
        scrollwheel: false
      });

      function mapCreate(markerLatLang, title, map) {
        var marker = new google.maps.Marker({
          position: markerLatLang,
          map: map
        });
        var infowindow = new google.maps.InfoWindow({
          content: title,
          maxWidth: 250
        });
        infowindow.open(map, marker);
      }

      function geocoderAdress(addresses, callback, map) {
        for (var i = 0; i < mapObject.map_markers.length; i++) {
          (function() {
            var address = scaleLiteMarkersBuffer[mapObject.map_markers[i]].location_address;
            var title = "<h4>" + scaleLiteMarkersBuffer[mapObject.map_markers[
              i]].title + "</h4>";
            var description = "<p>" + scaleLiteMarkersBuffer[mapObject.map_markers[
              i]].description + "</p>";
            geocoder.geocode({
              'address': address
            }, function(results, status) {
              callback(results[0].geometry.location, title +
                description, map);
            });
          })(i);
        }
      }
      geocoderAdress(scaleLiteMarkersBuffer, function(a, b, map) {
        mapCreate(a, b, map);
      }, map);
    }
  }
}
