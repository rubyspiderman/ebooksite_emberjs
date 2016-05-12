ESF.PageRoute = Ember.Route.extend({
  model: function(params) {
    return this.store.find('page', params.id);
  }
});
