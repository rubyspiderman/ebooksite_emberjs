ESF.ApplicationSerializer = DS.RESTSerializer.extend({
  extractArray: function(store, type, payload) {
    return this._super(store, type, payload);
  },
  keyForAttribute: function(key) {
    return Ember.String.decamelize(key);
  },
  keyForRelationship: function(key) {
    return Ember.String.decamelize(key);
  }
});
