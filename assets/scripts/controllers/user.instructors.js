ESF.UserInstructorsController = Ember.ObjectController.extend({
  ascending: false,
  column: 'username',
  hasInstructors: Ember.computed.bool('model.instructors.length'),
  init: function() {
    this.set('instructors', Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
      content: []
    }));
  },
  modify: function() {
    var self = this;

    this.get('model.instructors').then(function(instructors) {
      var filtered = instructors.get('content').filter(function(instructor) {
        if (!Ember.isEmpty(self.get('search'))) {
          var a = instructor.get('username').indexOf(self.get('search')) > -1;
          var b = instructor.get('display').toLowerCase().indexOf(self.get('search').toLowerCase()) == 0;

          if (!(a || b)) {
            return false;
          }
        }

        if (!Ember.isEmpty(self.get('before'))) {
          if (moment(self.get('before'), 'DD-MM-YYYY').isBefore(instructor.get('created'))) {
            return false;
          }
        }

        if (!Ember.isEmpty(self.get('after'))) {
          if (moment(self.get('after'), 'DD-MM-YYYY').isAfter(instructor.get('created'))) {
            return false;
          }
        }

        return true;
      });

      self.set('instructors', Ember.ArrayProxy.createWithMixins(Ember.SortableMixin, {
        content: filtered,
        sortAscending: self.get('ascending'),
        sortProperties: [self.get('column')]
      }));
    });
  }.observes('after', 'before', 'search', 'model.instructors'),
  pages: Ember.reduceComputed('instructors.@each.pages.length', {
    initialValue: 0,
    addedItem: function(accumulatedValue, instructor) {
      return accumulatedValue + instructor.get('pages.length');
    },
    removedItem: function(accumulatedValue, instructor) {
      return accumulatedValue - instructor.get('pages.length');
    }
  }),
  sortByColumn: function(column) {
    if (column == this.get('column')) {
      this.toggleProperty('instructors.sortAscending');
    }
    else {
      this.set('instructors.sortAscending', true);
      this.set('instructors.sortProperties', [column]);
    }

    this.set('column', column);
  },
  views: Ember.reduceComputed('instructors.@each.views', {
    initialValue: 0,
    addedItem: function(accumulatedValue, instructor) {
      return accumulatedValue + instructor.get('views');
    },
    removedItem: function(accumulatedValue, instructor) {
      return accumulatedValue - instructor.get('views');
    }
  }),
});
