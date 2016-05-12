ESF.PageDeleteRoute = Ember.Route.extend(SimpleAuth.AuthenticatedRouteMixin, {
  actions: {
    cancel: function() {
      this.transitionTo('page');
    },
    delete: function() {
      var self = this;

      this.currentModel.destroyRecord().then(function() {
        self.transitionTo('user');
      }, function(e) {
        
      });
    }
  }
});
