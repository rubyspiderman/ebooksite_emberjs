ESF.ApplicationController = Ember.Controller.extend({
  defaultBackground: '/images/bg-' + window.location.hostname + '.jpg',

  actions: {
    toggleMenu: function() {
      Ember.$('.application').toggleClass('menu-active');
    }
  },
  background: function() {
    return this.get('overriddenBackground') || this.get('defaultBackground');
  }.property('overriddenBackground'),
  setBackground: function(path) {
    if (path) {
      this.set('overriddenBackground', path);
    }
    else {
      this.set('overriddenBackground', null);
    }
  }
});
