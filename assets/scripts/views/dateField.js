ESF.DateField = Ember.TextField.extend({
  picker: null,

  didInsertElement: function() {
    var picker = new Pikaday({
      field: this.get('element'),
      firstDay: 1,
      format: 'DD-MM-YYYY',
      i18n: {
        previousMonth: Ember.I18n.t('datepicker.previous'),
        nextMonth: Ember.I18n.t('datepicker.next'),
        months: [
          Ember.I18n.t('datepicker.months.january'),
          Ember.I18n.t('datepicker.months.february'),
          Ember.I18n.t('datepicker.months.march'),
          Ember.I18n.t('datepicker.months.april'),
          Ember.I18n.t('datepicker.months.may'),
          Ember.I18n.t('datepicker.months.june'),
          Ember.I18n.t('datepicker.months.july'),
          Ember.I18n.t('datepicker.months.august'),
          Ember.I18n.t('datepicker.months.september'),
          Ember.I18n.t('datepicker.months.october'),
          Ember.I18n.t('datepicker.months.november'),
          Ember.I18n.t('datepicker.months.december')
        ],
        weekdays: [
          Ember.I18n.t('datepicker.days.sunday'),
          Ember.I18n.t('datepicker.days.monday'),
          Ember.I18n.t('datepicker.days.tuesday'),
          Ember.I18n.t('datepicker.days.wednesday'),
          Ember.I18n.t('datepicker.days.thursday'),
          Ember.I18n.t('datepicker.days.friday'),
          Ember.I18n.t('datepicker.days.saturday')
        ],
        weekdaysShort: [
          Ember.I18n.t('datepicker.days.short.sunday'),
          Ember.I18n.t('datepicker.days.short.monday'),
          Ember.I18n.t('datepicker.days.short.tuesday'),
          Ember.I18n.t('datepicker.days.short.wednesday'),
          Ember.I18n.t('datepicker.days.short.thursday'),
          Ember.I18n.t('datepicker.days.short.friday'),
          Ember.I18n.t('datepicker.days.short.saturday')
        ]
      },
      yearRange: [1900, 2020]
    });

    this.set('picker', picker);
  },
  modelChangedValue: function() {
    var picker = this.get('picker');

    if (picker && !Ember.empty(this.get('value'))) {
      picker.setMoment(moment(this.get('value'), 'DD-MM-YYYY'));
    }
  }.observes('value'),
  willDestroyElement: function() {
    var picker = this.get('picker');

    if (picker) {
      picker.destroy();
    }

    this.set('picker', null);
  }
});
