ESF.ProgressBarComponent = Ember.Component.extend({
  classNameBindings: ['error'],
  classNames: ['progress'],
  layoutName: 'components/progressBar',

  barStyle: function() {
    return 'width: ' + this.get('progress') + '%';
  }.property('progress')
});
