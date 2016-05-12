ESF.UserForgotRoute = Ember.Route.extend({
  beforeModel: function() {
    if (this.get('session.isAuthenticated')) {
      this.transitionTo('user', this.get('session.user_id'));
    }
  },
  setupController: function(controller, model) {
    this._super(controller, model);

    controller.set('error', false);
  },
  actions: {
    cancel: function() {
      this.transitionTo('user.login');
    },
    request: function() {
      var self = this;

      $.ajax({
        url: this.get('store').adapterFor('user').buildURL('user') + '/forgot',
        type: 'POST',
        data: {
          'identifier': self.controller.get('identifier')
        }
      }).done(function(data) {
        self.transitionTo('user.login');
      }).fail(function(xhr) {
        self.controller.set('error', true);
      });
    }
  }
});
