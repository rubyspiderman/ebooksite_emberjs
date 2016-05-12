ESF.UserProfileRoute = Ember.Route.extend(SimpleAuth.AuthenticatedRouteMixin, {
  setupController: function(controller, model) {
  	this._super(controller, model);

    if (ENV.messages && ENV.messages.profile) {
      controller.set('messages', ENV.messages.profile);
    }
  }
});
