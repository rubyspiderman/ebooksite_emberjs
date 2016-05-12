ESF.UserEditController = Ember.ObjectController.extend({
  isPersisted: function() {
    return !this.get('model.isNew');
  }.property('model.isNew')
});
