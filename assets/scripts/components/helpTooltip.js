ESF.HelpTooltipComponent = Ember.Component.extend(Ember.I18n.TranslateableProperties, {
  layoutName: 'components/helpTooltip',
  didInsertElement: function() {
    this.$('.form-help-button').tooltip({
      placement: 'bottom'
    });
  }
});
