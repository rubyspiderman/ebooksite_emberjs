ESF.UserPasswordController = Ember.ObjectController.extend({
  confirm: '',
  error: false,
  incomplete: function() {
    if (Ember.isEmpty(this.get('password'))) {
      return true;
    }

    if (this.get('password') != this.get('confirm')) {
      return true;
    }

    return false;
  }.property('password', 'confirm')
});
