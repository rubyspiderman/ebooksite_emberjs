ESF.Comment = DS.Model.extend({
  user: DS.belongsTo('user', {
    async: true
  }),
  page: DS.belongsTo('page', {
    async: true
  }),
  photos: DS.hasMany('photo'),
  created: DS.attr('date'),
  name: DS.attr(),
  body: DS.attr(),
  approved: function() {
    return this.get('photos').filterBy('unapproved', false);
  }.property('photos.@each.unapproved'),
  unapproved: function() {
    return this.get('photos').filterBy('unapproved');
  }.property('photos.@each.unapproved')
});
