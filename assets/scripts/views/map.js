ESF.MapView = Ember.View.extend({
  classNames: ['map'],
  templateName: 'views/map',

  didInsertElement: function() {
    var bounds = new google.maps.LatLngBounds();
    var locationPin = null;
    var markers = [];
    var options = {
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      scrollwheel: false,
      streetViewControl: false
    };
    var self = this;

    var map = new google.maps.Map(this.get('element'), options);

    if (this.get('controller.model.photoMappingAllowed')) {
      var photos = this.get('controller.model.photos');

      photos.forEach(function(item) {
        if (item.get('latitude')) {
          var position = new google.maps.LatLng(item.get('latitude'), item.get('longitude'));
          bounds.extend(position);

          var marker = new google.maps.Marker({
            icon: '/images/marker-photo.png',
            map: map,
            position: position
          });

          markers.push(marker);

          google.maps.event.addListener(marker, 'click', function() {
            self.get('controller').trigger('showPhoto', photos.indexOf(item));
          });
        }
      });
    }

    this.get('controller.model.logs').forEach(function(item) {
      var linestrings = item.get('linestrings');

      if (linestrings) {
        for (var i = 0; i < linestrings.length; i++) {
          for (var j = 0; j < linestrings[i].length; j++) {
            bounds.extend(linestrings[i][j]);
          }

          markers.push(new google.maps.Polyline({
            map: map,
            path: linestrings[i],
            strokeColor: '#c80f1d'
          }));

          markers.push(new google.maps.Marker({
            icon: '/images/marker-start.png',
            map: map,
            position: linestrings[i][0]
          }));

          markers.push(new google.maps.Marker({
            icon: '/images/marker-end.png',
            map: map,
            position: linestrings[i][linestrings[i].length - 1]
          }));
        }
      }
    });

    this.get('controller').on('setLocationPin', this, function(position) {
      if (!locationPin) {
        locationPin = new google.maps.Marker({
          position: position,
          map: map,
          icon: '/images/marker-location.png'
        });
      }
      else {
        locationPin.setPosition(position);
      }
    });

    map.fitBounds(bounds);
  }
});
