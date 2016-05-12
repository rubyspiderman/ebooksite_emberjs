ESF.UserSubpagesController = Ember.ObjectController.extend({
  ascending: false,
  column: 'created',
  hasPages: Ember.computed.bool('model.subpages.length'),
  init: function() {
    this.set('pages', Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
      content: []
    }));
  },
  modify: function() {
    var self = this;

    this.get('model.subpages').then(function(pages) {
      var filtered = pages.get('content').filter(function(page) {
        if (!Ember.isEmpty(self.get('search'))) {
          var a = page.get('user.username').indexOf(self.get('search')) > -1;
          var b = page.get('user.display').toLowerCase().indexOf(self.get('search').toLowerCase()) == 0;

          if (!(a || b)) {
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
  }.observes('end', 'search', 'start', 'model.subpages'),
  sortByColumn: function(column) {
    if (column == this.get('column')) {
      this.toggleProperty('pages.sortAscending');
    }
    else {
      this.set('pages.sortAscending', true);
      this.set('pages.sortProperties', [column]);
    }

    this.set('column', column);
  },
  views: function() {
    return this.get('pages').reduce(function(previous, page) {
      return previous + page.get('views');
    }, 0);
  }.property('pages.@each.views')
});
