ESF.UserPagesController = Ember.ObjectController.extend({
  ascending: false,
  column: 'created',
  hasPages: Ember.computed.bool('model.pages.length'),
  init: function() {
    this.set('pages', Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
      content: []
    }));
  },
  modify: function() {
    var self = this;

    this.get('model.pages').then(function(pages) {
      var filtered = pages.get('content').filter(function(page) {
        if (!Ember.isEmpty(self.get('code'))) {
          if (page.get('code').toString().indexOf(self.get('code')) != 0) {
            return false;
          }
        }

        if (!Ember.isEmpty(self.get('end'))) {
          if (moment(self.get('end'), 'DD-MM-YYYY').isBefore(page.get('created'))) {
            return false;
          }
        }

        if (!Ember.isEmpty(self.get('start'))) {
          if (moment(self.get('start'), 'DD-MM-YYYY').isAfter(page.get('created'))) {
            return false;
          }
        }

        return true;
      });

      self.set('pages', Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
        content: filtered,
        sortAscending: self.get('ascending'),
        sortProperties: [self.get('column')]
      }));
    });
  }.observes('code', 'end', 'start', 'model.pages'),
  sortByColumn: function(column) {
    if (column == this.get('column')) {
      this.toggleProperty('pages.sortAscending');
    }
    else {
      this.set('pages.sortAscending', true);
      this.set('pages.sortProperties', [column]);
    }

    this.set('column', column);
  }
});
