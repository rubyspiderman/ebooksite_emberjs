ESF.RichTextAreaComponent = Ember.Component.extend({
  classNames: ['rich-text-area'],
  layoutName: 'components/richTextArea',

  didInsertElement: function() {
    Ember.run.scheduleOnce('afterRender', this, this.hasRendered);
  },
  hasRendered: function() {
    var self = this;

    var editor = new wysihtml5.Editor(this.$('.multiple')[0], {
      parserRules: {
        tags: {
          a: {
            check_attributes: {
              href: 'url'
            }
          },
          b:      {},
          br:     {},
          em:     {},
          i:      {},
          li:     {},
          ol:     {},
          p:      {},
          strong: {},
          ul:     {},
        }
      },
      toolbar: this.$('.toolbar')[0],
      useLineBreaks: true
    });

    editor.observe('change', function() {
      self.set('value', editor.getValue());
    });

    this.set('editor', editor);
  },
  willDestroyElement: function() {
    if (this.get('editor')) {
      this.get('editor').stopObserving('change');
      this.set('editor', null);
    }
  }
});
