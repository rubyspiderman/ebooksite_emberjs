ESF.InstructorView = Ember.View.extend({
  classNames: ['instructor'],
  templateName: 'views/instructor',
  actions: {
    toggleBio: function() {
      this.$('.bio').slideToggle();
    }
  },
  didInsertElement: function() {
    this.$('.bio').hide();
  }
});
