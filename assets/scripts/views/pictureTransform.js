ESF.PictureTransformView = Ember.View.extend({
  classNames: ['picture-transform'],
  templateName: 'views/pictureTransform',

  didInsertElement: function() {
    var self = this;

    this.$('img').load(function() {
      var guillotine = self.$(this).guillotine({
        eventOnChange: 'guillotine:change',
        height: 640,
        init: {
          angle: self.get('context.angle'),
          scale: self.get('context.scale'),
          x: self.get('context.x'),
          y: self.get('context.y')
        },
        width: 640
      }).on('guillotine:change', function(event, data, action) {
        self.set('context.angle', data.angle);
        self.set('context.scale', data.scale);
        self.set('context.x', data.x);
        self.set('context.y', data.y);
      });

      self.set('guillotine', guillotine);
    });
  },
  willDestroyElement: function() {
    var guillotine = this.get('guillotine');

    if (guillotine) {
      guillotine.remove();
    }

    this.set('guillotine', null);
  }
});
