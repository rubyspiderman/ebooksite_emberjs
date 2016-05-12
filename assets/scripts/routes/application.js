ESF.ApplicationRoute = Ember.Route.extend(SimpleAuth.ApplicationRouteMixin, {
  actions: {
    error: function(error, transition) {
      if (error && error.status == 402) {
        return this.transitionTo('user.inactive');
      }

      return true;
    },
    willTransition: function() {
      $('.application').removeClass('menu-active');
    }
  },
  setupController: function(controller) {
    controller.set('accessedPages', Ember.A());

    Ember.$.ajaxPrefilter(function(options, _, xhr) {
      Ember.$('.application').addClass('loading');

      xhr.always(function() {
        Ember.$('.application').removeClass('loading');
      });
    });

    Ember.$(window).on('load resize', function() {
      Ember.$('.application').css('min-height', Ember.$(window).height() - Ember.$('.footer-menu').innerHeight() + 'px');
    });
  }
});
