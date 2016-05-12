ESF.ElevationView = Ember.View.extend({
  chart: null,
  classNames: ['elevation'],
  es: null,
  templateName: 'views/elevation',

  didInsertElement: function() {
    var chart = new google.visualization.ColumnChart(this.$('.graph')[0]);
    var es = new google.maps.ElevationService();
    var self = this;

    this.set('distance', Math.floor(google.maps.geometry.spherical.computeLength(this.get('context'))));

    es.getElevationAlongPath({
      path: this.get('context'),
      samples: 440
    }, function(results) {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Elevation');

      for (var i = 0; i < results.length; i++) {
        data.addRow([results[i].elevation]);
      }

      chart.draw(data, {
        legend: 'none',
        titleY: 'Elevation (m)'
      });

      self.set('initial', Math.floor(results[0].elevation));
      self.set('final', Math.floor(results[results.length - 1].elevation));
      self.set('maximum', Math.floor(results.reduce(function(previous, current) {
        return Math.max(previous, current.elevation);
      }, -Infinity)));
      self.set('minimum', Math.floor(results.reduce(function(previous, current) {
        return Math.min(previous, current.elevation);
      }, Infinity)));

      google.visualization.events.addListener(chart, 'onmouseover', function(e) {
        self.get('controller').trigger('setLocationPin', results[e.row].location);
      });
    });

    this.set('chart', chart);
    this.set('es', es);
  }
});
