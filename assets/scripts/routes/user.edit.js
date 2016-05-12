ESF.UserEditRoute = Ember.Route.extend(SimpleAuth.AuthenticatedRouteMixin, {
  setupController: function(controller, model) {
    this._super(controller, model);

    var schools = controller.get('store').find('user', {
      role: 2
    });

    schools.then(function() {
      controller.set('schools', Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
        content: schools.get('content'),
        sortAscending: true,
        sortProperties: ['name']
      }));
    });
  },
  actions: {
    cancel: function() {
      this.transitionTo('user');
    },
    deletePicture: function(picture) {
      picture.destroyRecord();
    },
    save: function() {
      var self = this;

      var model = this.modelFor(this.routeName);
      model.save().then(function() {
        self.transitionTo('user');
      }, function() {
        // error
      });
    },
    willTransition: function() {
      var model = this.modelFor(this.routeName);
      model.rollback();

      return true;
    }
  }
});
