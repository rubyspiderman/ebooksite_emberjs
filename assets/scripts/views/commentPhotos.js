ESF.CommentPhotosView = Ember.View.extend({
  didInsertElement: function() {
    var self = this;

    var data = this.get('context.approved').map(function(photo) {
      return {
        image: photo.get('display'),
        thumb: photo.get('thumbnail'),
        big: photo.get('full')
      };
    });

    var galleria = Galleria.run(this.get('element'), {
      dataSource: data,
      height: 0.75,
      imageCrop: 'landscape',
      showCounter: false,
      swipe: 'enforced'
    });
  }
});
