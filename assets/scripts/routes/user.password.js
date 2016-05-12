ESF.UserPasswordRoute = Ember.Route.extend(SimpleAuth.AuthenticatedRouteMixin, {
  setupController: function(controller, model) {
    this._super(controller, model);

    controller.set('confirm', '');
    controller.set('error', false);
  },
  actions: {
    cancel: function() {
      this.transitionTo('user');
    },
    save: function() {
      var self = this;

      this.controller.set('error', false);

      var model = this.modelFor(this.routeName);
      model.save().then(function() {
        self.transitionTo('user');
      }, function() {
        self.controller.set('error', 'error.server');
      });
    },
    willTransition: function() {
      var model = this.modelFor(this.routeName);
      model.rollback();

      return true;
    }
  }
});
