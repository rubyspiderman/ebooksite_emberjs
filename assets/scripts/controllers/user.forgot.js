ESF.UserForgotController = Ember.ObjectController.extend({
  error: false,
  identifier: '',
  incomplete: function() {
    return Ember.isEmpty(this.get('identifier'));
  }.property('identifier')
});
