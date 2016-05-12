ESF.TypeAheadComponent = Ember.TextField.extend({
  didInsertElement: function() {
    var self = this;

    this._super();

    if (jQuery.isFunction(this.get('data').then)) {
      this.get('data').then(function(data) {
        self.initialize(data);
      });
    }
    else  {
      this.initializeTypeahead(this.get('data'));
    }
  },
  initialize: function(data) {
    var self = this;

    var typeahead = this.$().typeahead({
      minLength: 1
    }, {
      name: 'models',
      displayKey: 'value',
      source: this.suggestionEngine(data)
    }).on('typeahead:selected typeahead:autocompleted', function(event, item) {
      self.get('destination').addObject(item.model);
      self.get('typeahead').typeahead('val', '');
    });

    this.set('typeahead', typeahead);
  },
  suggestionEngine: function(models) {
    var self = this;

    return function matches(query, callback) {
      var matches = [], regex = new RegExp(query, 'i');

      models.forEach(function(model, i) {
        if (regex.test(model.get(self.get('name')))) {
          matches.push({
            value: model.get(self.get('name')),
            model: model
          });
        }
      });

      callback(matches);
    };
  }
});
