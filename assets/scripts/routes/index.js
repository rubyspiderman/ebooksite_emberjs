ESF.IndexRoute = Ember.Route.extend({
  setupController: function(controller) {
    controller.set('error', false); console.log(ENV);

    if (ENV.messages && ENV.messages.home) {
      controller.set('messages', ENV.messages.home);
    }
  },
  actions: {
    lookup: function() {
      var self = this;

      this.controller.set('error', false);

      this.store.find('page', {
        code: this.controller.get('code')
      }).then(function(pages) {
        self.controller.set('code', null);

        var page = pages.get('firstObject');

        self.controller.accessPage(page);
        self.transitionTo('page', page);
      }, function (xhr) {
        self.controller.set('error', true);
      });
    }
  }
});
