Ember.Handlebars.registerBoundHelper('format-date', function(value, options) {
  return moment(value).format('DD-MM-YYYY');
});
