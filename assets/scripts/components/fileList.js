ESF.FileListComponent = Ember.Component.extend({
  layoutName: 'components/fileList',

  actions: {
    deleteFile: function(file) {
      file.destroyRecord();
    },
    setBackground: function(file) {
      var page = this.get('backgroundTarget');

      if (page) {
        page.set('background', file);
      }
    }
  },

  didInsertElement: function() {
    Ember.run.scheduleOnce('afterRender', this, this.hasRendered);
  },
  hasRendered: function() {
    var self = this;

    this.$('.thumbnail').height(this.$('.item').width() * 0.75);

    var height = this.$('.item').height();

    this.$().sortable({
      items: '.item',
      placeholder: '<div class="placeholder small-12 medium-4 columns" style="height: ' + height + 'px;"></div>'
    }).bind('sortupdate', function(e, ui) {
      var weights = [];

      self.$('.item').each(function(index) {
        weights[$(this).data('id')] = index;
      });

      self.get('files').forEach(function(file) {
        file.set('weight', weights[file.get('id')]);
      });
    });
  },
  modelHasChanged: function() {
    this.rerender();
  }.observes('files.@each'),
  willDestroyElement: function() {
    //this.$().sortable('destroy');
  },
  willInsertElement: function() {
    this.set('sorted', this.get('files').sortBy('weight'));
  }
});
