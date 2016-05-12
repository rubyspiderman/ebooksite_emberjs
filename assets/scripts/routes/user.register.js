ESF.UserRegisterRoute = Ember.Route.extend({
  controllerName: 'user.edit',
  templateName: 'user.edit',

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
      this.get('currentModel').destroyRecord();

      this.transitionTo('user.login');
    },
    deletePicture: function(picture) {
      picture.destroyRecord();
    },
    save: function() {
      var self = this;

      this.get('currentModel').save().then(function() {
        self.transitionTo('user.verify');
      }, function() {
        // error
      });
    }
  },
  model: function() {
    return this.store.createRecord('user');
  }
});
