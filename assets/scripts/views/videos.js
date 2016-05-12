ESF.VideosView = Ember.View.extend({
  didInsertElement: function() {
    var data = this.get('context.videos').map(function(video) {
      var description = video.get('description') || '';

      if (video.get('download')) {
        description += ' <a href="' + video.get('download') + '" target="_blank">(' + Ember.I18n.t('page.videos.download') + ')</a>';
      }

      return {
        iframe: video.get('video'),
        thumb: video.get('thumbnail'),
        title: description
      };
    });

    Galleria.run(this.get('element'), {
      dataSource: data,
      height: 0.75,
      imageCrop: true,
      showCounter: false
    });
  }
});
