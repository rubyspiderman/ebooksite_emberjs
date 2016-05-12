ESF.FileUpload = Ember.Object.extend({
  error: false,
  file: null,
  message: 'upload.starting',
  method: 'POST',
  name: '',
  progress: 0,
  promise: null,
  size: null,
  type: null,
  url: null,
  xhr: null,

  cancel: function() {
    var xhr = this.get('xhr');

    if (xhr) {
      xhr.abort();
    }
  },
  init: function() {
    this._super();

    var file = this.get('file');

    this.set('name', file.name);
    this.set('promise', Ember.Deferred.create());
    this.set('size', file.size);
    this.set('type', file.type);
  },
  upload: function() {
    var self = this;

    var xhr = $.ajax({
      url: self.get('url'),
      type: self.get('method'),
      data: self.get('file'),
      processData: false,
      contentType: self.get('type'),
      xhr: function() {
        var xhr = $.ajaxSettings.xhr();

        xhr.upload.onprogress = function(e) {
          self.set('progress', Math.round(e.loaded / e.total * 100));
        };

        return xhr;
      }
    }).done(function(data) {
      self.set('message', 'upload.complete');
      self.get('promise').resolve(data);
    }).fail(function(xhr) {
      try {
        var data = JSON.parse(xhr.responseText);
        self.set('message', data.meta.error);
      }
      catch (e) {
        self.set('message', 'error.server')
      }
      finally {
        self.set('error', true);
        self.get('promise').reject();
      }
    });

    this.set('message', 'upload.uploading');
    this.set('xhr', xhr);

    return this.get('promise');
  }
});
