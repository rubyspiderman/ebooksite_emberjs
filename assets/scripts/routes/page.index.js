ESF.PageIndexRoute = Ember.Route.extend({
  actions: {
    approvePhoto: function(photo) {
      photo.set('unapproved', false).save();
    },
    deleteComment: function(comment) {
      comment.destroyRecord();
    },
    removePhoto: function(photo) {
      photo.destroyRecord();
    }
  },
  resetController: function(controller) {
    controller.setBackground(false);
  },
  setupController: function(controller, page) {
    this._super(controller, page);

    var accessed = controller.get('accessed');
    var allowed = page.get('sharingAllowed');
    var owner = controller.get('owner');

    if (!accessed && !allowed && !owner) {
      this.transitionTo('index');
    }
  }
});
