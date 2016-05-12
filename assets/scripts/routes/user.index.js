ESF.UserIndexRoute = Ember.Route.extend(SimpleAuth.AuthenticatedRouteMixin, {
  afterModel: function(user) {
    this.transitionTo('user.profile', user);
  }
});
