ESF.CommentFormComponent = Ember.Component.extend({
  body: '',
  classNames: ['comment-form'],
  error: false,
  name: '',
  photos: [],
  templateName: 'components/commentForm',

  actions: {
    post: function() {
      var self = this;
      var store = this.get('parentView.controller.store');

      this.set('error', false);

      var comment = store.createRecord('comment', {
        page: store.getById('page', this.get('page')),
        name: this.get('name'),
        body: this.get('body')
      });

      this.get('photos').forEach(function(photo) {
        comment.get('photos').pushObject(photo);
      });

      comment.save().then(function(model) {
        self.get('photos').clear();
        self.set('body', '');
        self.set('name', '');
      }, function(xhr) {
        comment.deleteRecord();
        self.set('error', true);
      });
    },
    removePhoto: function(photo) {
      this.get('photos').removeObject(photo);
    }
  }
});
