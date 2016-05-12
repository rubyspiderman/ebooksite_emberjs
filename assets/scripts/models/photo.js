ESF.Photo = DS.Model.extend({
  comment: DS.belongsTo('comment', {
    async: true
  }),
  user: DS.belongsTo('user', {
    async: true
  }),
  page: DS.belongsTo('page', {
    async: true
  }),
  description: DS.attr(),
  unapproved: DS.attr('boolean'),
  weight: DS.attr('number'),
  thumbnail: DS.attr(),
  display: DS.attr(),
  full: DS.attr(),
  latitude: DS.attr(),
  longitude: DS.attr()
});
