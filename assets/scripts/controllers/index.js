ESF.IndexController = Ember.Controller.extend({
  needs: ['application'],
  accessPage: function(page) {
    this.get('controllers.application.accessedPages').addObject(page);
  }
});
