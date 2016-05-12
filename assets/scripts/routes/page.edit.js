ESF.PageEditRoute = Ember.Route.extend(SimpleAuth.AuthenticatedRouteMixin, {
  setupController: function(controller, model) {
    this._super(controller, model);

    controller.set('error', null).set('schoolInstructors', controller.get('store').find('user', {
      role: 1
    }));
  },
  actions: {
    cancel: function() {
      this.transitionTo('page');
    },
    removeInstructor: function(instructor) {
      var model = this.modelFor(this.routeName);

      model.get('instructors').removeObject(instructor);
    },
    save: function() {
      var controller = this.get('controller');
      var self = this;

      controller.set('error', null);

      var model = this.modelFor(this.routeName);
      model.save().then(function() {
        self.transitionTo('page');
      }, function(xhr) {
        if (xhr && xhr.responseJSON && xhr.responseJSON.meta) {
          controller.set('error', xhr.responseJSON.meta.error);
        }
      });
    },
    willTransition: function() {
      var model = this.modelFor(this.routeName);
      model.rollback();

      return true;
    }
  }
});
