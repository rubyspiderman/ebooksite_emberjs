ESF.ComposedDateField = Ember.Component.extend({
  classNames: ['composed-date'],
  templateName: 'components/composedDateField',

  componentChangedValue: function() {
    Ember.run.once(this, 'setValue');
  }.observes('day', 'month', 'year'),
  didInsertElement: function() {
    if (this.get('value')) {
      var date = moment(this.get('value'), 'DD-MM-YYYY');

      this.set('day', date.date()).set('month', date.month() + 1).set('year', date.year());
    }
  },
  setValue: function() {
    var day = this.get('day'), month = this.get('month') - 1, year = this.get('year');

    if (day != null && month != null && year != null) {
      var date = moment([year, month, day]);

      this.set('value', date.format('DD-MM-YYYY'));
    }
    else {
      this.set('value', null);
    }
  },
  willInsertElement: function() {
    var years = [], months = [], days = [];

    for (var i = 1900; i < 2015; i++) {
      years.push(i);
    }

    for (var i = 1; i < 13; i++) {
      months.push(i);
    }

    for (var i = 1; i < 32; i++) {
      days.push(i);
    }

    this.set('years', years).set('months', months).set('days', days);
  }
});
