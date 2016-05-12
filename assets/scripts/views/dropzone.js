ESF.DropzoneView = Ember.View.extend({
  classNames: ['dropzone'],
  classNameBindings: ['over'],
  hiddenInput: null,
  over: false,
  templateName: 'views/dropzone',

  click: function() {
    this.get('hiddenInput').click();
  },
  destroyHiddenElement: function() {
    if (this.get('hiddenInput')) {
      this.get('hiddenInput').remove();
    }
  }.on('willDestroyElement'),
  dragEnter: function(e) {
    e.preventDefault();
    return false;
  },
  dragLeave: function() {
    this.set('over', false);
  },
  dragOver: function(e) {
    e.preventDefault();
    this.set('over', true);
    return false;
  },
  drop: function(e) {
    e.preventDefault();
    this.set('over', false);

    if (this.eventHasFiles(e)) {
      for (var i = 0; i < e.dataTransfer.files.length; i++) {
        this.get('parentView').send('addFile', e.dataTransfer.files[i]);
      }
    }

    return false;
  },
  eventHasFiles: function(e) {
    try {
      return e.dataTransfer.types.contains('Files');
    }
    catch (ex) {}

    return false;
  },
  setUpHiddenInput: function() {
    var self = this;

    this.destroyHiddenElement();

    this.set('hiddenInput', $('<input>').attr({
      multiple: 'multiple',
      type: 'file'
    }).css({
      height: 0,
      left: 0,
      position: 'absolute',
      top: 0,
      visibility: 'hidden',
      width: 0
    }).on('change', function() {
      var files = this.files;

      if (files.length) {
        for (var i = 0; i < files.length; i++) {
          self.get('parentView').send('addFile', files[i]);
        }
      }

      return self.setUpHiddenInput();
    }).appendTo('body'));
  }.on('didInsertElement')
});
