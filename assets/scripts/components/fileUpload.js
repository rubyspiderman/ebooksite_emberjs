ESF.FileUploadComponent = Ember.Component.extend({
  adapter: null,
  classNames: ['file-upload'],
  files: null,
  layoutName: 'components/fileUpload',
  model: null,
  store: null,

  actions: {
    addFile: function(file) {
      var self = this;

      var upload = ESF.FileUpload.create({
        file: file,
        url: this.get('adapter').buildURL(this.get('model'))
      });

      if (upload.get('error')) {
        return;
      }

      this.get('files').pushObject(upload).upload().then(function(data) {
        var model = self.get('model');

        if (data.hasOwnProperty(model)) {
          self.get('destination').pushObjects(self.get('store').pushMany(model, [data[model]]));
        }

        self.get('files').removeObject(upload);
      });
    },
    removeFile: function(upload) {
      var files = this.get('files');
      files.removeObject(upload);
    }
  },

  init: function() {
    this._super();

    this.set('files', Ember.ArrayProxy.create({
      content: []
    }));
    this.set('store',
      this.get('parentView.controller.store') ||
      this.get('parentView.controller.parentView.controller.store') // ugh
    );
    this.set('adapter', this.get('store').adapterFor(this.get('model')));
  },
  willDestroyElement: function() {
    this.get('files').forEach(function(item) {
      item.cancel();
    });
  }
});
