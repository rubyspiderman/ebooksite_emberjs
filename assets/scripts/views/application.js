ESF.ApplicationView = Ember.View.extend({
  backgroundHasChanged: function() {
    Ember.$('.application').css('background-image', 'url("' + this.get('controller.background') + '")');
  }.observes('controller.background').on('didInsertElement')
});
