ESF.PageAddRoute = Ember.Route.extend(SimpleAuth.AuthenticatedRouteMixin, {
  templateName: 'page.edit',

  setupController: function(controller, model) {
    this._super(controller, model);

    controller.set('schoolInstructors', controller.get('store').find('user', {
      role: 1
    }));
  },
  actions: {
    cancel: function() {
      this.transitionTo('user', this.get('session.user'));
    },
    deleteFile: function(file) {
      file.destroyRecord();
    },
    removeInstructor: function(instructor) {
      var model = this.modelFor(this.routeName);

      model.get('instructors').removeObject(instructor);
    },
    save: function() {
      var self = this;

      this.currentModel.save().then(function() {
        self.transitionTo('page', self.currentModel);
      }, function() {
        // error
      });
    }
  },
  model: function() {
    return this.store.createRecord('page'); 
  }
});
