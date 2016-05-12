ESF.PageIndexController = Ember.ObjectController.extend(Ember.Evented, {
  needs: ['application'],
  accessed: function() {
    return this.get('controllers.application.accessedPages').contains(this.get('model'));
  }.property('controllers.application.accessedPages'),
  backgroundHasChanged: function() {
    this.setBackground(this.get('model.background.full'));
  }.observes('model.background').on('init'),
  commentSorting: ['created:asc'],
  comments: Ember.computed.sort('model.comments', 'commentSorting'),
  map: function() {
    var logs = this.get('model.logs.length');
    var photos = this.get('model.photoMappingAllowed') && this.get('model.photos').reduce(function(previous, item) {
      if (item.get('latitude')) {
        return previous + 1;
      }

      return previous;
    }, 0);

    return logs || photos;
  }.property('model.photos', 'model.logs', 'model.photoMappingAllowed'),
  owner: function() {
    var user = this.get('session.user_id'), role = this.get('session.user_role');

    if (!user) {
      return false;
    }

    if (role == 3) {
      return true;
    }

    var instructor = user == this.get('model.user.id');
    var school = user == this.get('model.user.school.id');

    return instructor || school;
  }.property('model.user', 'session.user_id'),
  photoSorting: ['weight:asc'],
  photos: Ember.computed.sort('model.photos', 'photoSorting'),
  setBackground: function(path) {
    if (!path) {
      this.get('controllers.application').setBackground(false);
    }
    else {
      this.get('controllers.application').setBackground(this.get('model.background.full'));
    }
  },
  videoSorting: ['weight:asc'],
  videos: Ember.computed.sort('model.videos', 'videoSorting'),
});
