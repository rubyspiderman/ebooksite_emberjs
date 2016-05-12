ESF.Log = DS.Model.extend({
  user: DS.belongsTo('user', {
    async: true
  }),
  page: DS.belongsTo('page', {
    async: true
  }),
  description: DS.attr(),
  weight: DS.attr('number'),
  thumbnail: DS.attr(),
  download: DS.attr(),
  paths: DS.attr(),

  linestrings: function() {
    var linestrings = [];

    this.get('paths').forEach(function(path) {
      var linestring = [];

      for (var i = 0; i < path.length; i++) {
        linestring.push(new google.maps.LatLng(path[i].latitude, path[i].longitude));
      }

      linestrings.push(linestring);
    });

    return linestrings;
  }.property('paths')
});
