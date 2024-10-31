// google maps render frontend map
var slt_gmaps_init = function() {
  "use strict";

  // prevent google maps from loading roboto
  var head = document.getElementsByTagName('head')[0];
  // Save the original method
  var insertBefore = head.insertBefore;
  // Replace it!
  head.insertBefore = function (newElement, referenceElement) {
    if (newElement.href && newElement.href.indexOf('https://fonts.googleapis.com/css?family=Roboto') === 0) {
      return;
    }
    insertBefore.call(head, newElement, referenceElement);
  };

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

      function addMarker(position, title, icon, color) {
        var marker = new google.maps.Marker({
          position: position,
          map: map,
          icon: {
            url: ' ',
            labelOrigin: new google.maps.Point(12, 20)
          },
          label: {
            fontFamily: 'Material Icons',
            fontSize: '40px',
            color: color,
            text: icon
          }
        });
        var infowindow = new google.maps.InfoWindow({
          content: title,
          maxWidth: 250
        });
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
        setTimeout(function(){ infowindow.open(map,marker); }, 500);
      }

      for (var i = 0; i < mapObject.map_markers.length; i++) {
        var marker_lat = parseFloat(scaleLiteMarkersBuffer[mapObject.map_markers[i]].marker_lat);
        var marker_lng = parseFloat(scaleLiteMarkersBuffer[mapObject.map_markers[i]].marker_lng);
        var title = "<h4>" + scaleLiteMarkersBuffer[mapObject.map_markers[
          i]].title + "</h4>";
        var description = "<p>" + scaleLiteMarkersBuffer[mapObject.map_markers[
          i]].description + "</p>";
        var marker_icon = (scaleLiteMarkersBuffer[mapObject.map_markers[
          i]].icon) ? scaleLiteMarkersBuffer[mapObject.map_markers[
          i]].icon : 'place'
        var marker_color = (scaleLiteMarkersBuffer[mapObject.map_markers[
          i]].icon_color) ? scaleLiteMarkersBuffer[mapObject.map_markers[
          i]].icon_color : '#3E82F7'

        addMarker({lat: marker_lat, lng: marker_lng}, title+description, marker_icon, marker_color);
      }
    }
  }
}
