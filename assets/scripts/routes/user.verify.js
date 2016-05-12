ESF.UserVerifyRoute = Ember.Route.extend({
  beforeModel: function() {
    if (this.get('session.isAuthenticated')) {
      this.transitionTo('user', this.get('session.user_id'));
    }
  }
});
