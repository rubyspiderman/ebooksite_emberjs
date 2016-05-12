ESF.VideoUploadComponent = ESF.FileUploadComponent.extend({
  actions: {
    addFile: function(file) {
      var self = this;

      var upload = this.get('files').pushObject(ESF.FileUpload.create({
        file: file
      }));

      $.ajax({
        url: self.get('adapter').buildURL(self.get('model')) + '/ticket'
      }).done(function(data) {
        upload.set('method', 'PUT');
        upload.set('url', data.upload);

        upload.upload().then(function() {
          upload.set('message', 'upload.finalizing');

          $.ajax({
            url: self.get('adapter').buildURL(self.get('model')),
            type: 'POST',
            data: JSON.stringify({
              finish: data.finish
            }),
            processData: false,
            contentType: 'application/json'
          }).done(function(data) {
            var model = self.get('model');

            if (data.hasOwnProperty(model)) {
              self.get('destination').pushObjects(self.get('store').pushMany(model, [data[model]]));
            }

            self.get('files').removeObject(upload);
          }).fail(function(xhr) {
            try {
              var data = JSON.parse(xhr.responseText);
              upload.set('message', data.meta.error);
            }
            catch (e) {
              upload.set('message', 'error.vimeo.general');
            }
            finally {
              upload.set('error', true);
            }
          });

        });
      }).fail(function() {
        upload.set('error', true);
        upload.set('message', 'error.vimeo.general');
        upload.set('progress', 100);
      });
    }
  }
});
