ESF.PictureUploadComponent = Ember.Component.extend({
  adapter: null,
  file: null,
  layoutName: 'components/pictureUpload',
  model: 'picture',
  store: null,

  actions: {
    addFile: function(file) {
      var self = this;

      var upload = ESF.FileUpload.create({
        file: file,
        url: this.get('adapter').buildURL(this.get('model'))
      });

      this.set('file', upload);

      this.get('file').upload().then(function(data) {
        var model = self.get('model');

        if (data.hasOwnProperty(model)) {
          self.set('destination', self.get('store').pushMany(model, [data[model]]).get('firstObject'));
        }

        self.set('file', null);
      });
    }
  },

  init: function() {
    this._super();

    this.set('store', this.get('parentView.controller.store'));
    this.set('adapter', this.get('store').adapterFor(this.get('model')));
  }
});
