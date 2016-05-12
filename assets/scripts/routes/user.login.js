ESF.UserLoginRoute = Ember.Route.extend({
  beforeModel: function() {
    if (this.get('session.isAuthenticated')) {
      this.transitionTo('user', this.get('session.user_id'));
    }
  },
  setupController: function(controller) {
    controller.set('error', false);
  },
  actions: {
    login: function() {
      var self = this;

      this.controller.set('error', false);

      this.get('session').authenticate('authenticator:oauth2', {
        identification: this.controller.get('username'),
        password: this.controller.get('password')
      }).then(function() {
        self.transitionTo('user', self.get('session.user_id'));
      }, function(xhr) {
        self.controller.set('error', true);
        self.controller.set('password', null);
      });
    },
    sessionAuthenticationFailed: function(error) {
      this.controller.set('error', true);
    }
  }
});
