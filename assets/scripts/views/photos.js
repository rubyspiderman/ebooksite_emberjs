ESF.PhotosView = Ember.View.extend({
  didInsertElement: function() {
    var self = this;

    var data = this.get('context.photos').map(function(photo) {
      var download = '<a href="' + photo.get('full') + '">(' + Ember.I18n.t('page.photos.download') + ')</a>';

      if (photo.get('description')) {
        var description = photo.get('description') + ' ' + download;
      }
      else {
        var description = download;
      }

      return {
        image: photo.get('display'),
        thumb: photo.get('thumbnail'),
        big: photo.get('full'),
        title: description
      };
    });

    var galleria = Galleria.run(this.get('element'), {
      dataSource: data,
      height: 0.75,
      imageCrop: 'landscape',
      showCounter: false,
      swipe: 'enforced'
    });

    this.get('controller').on('showPhoto', this, function(index) {
      Ember.$('html, body').animate({
        scrollTop: self.$().offset().top - 100
      }, 500);

      galleria.ready(function() {
        this.show(index);
      });
    });
  }
});
