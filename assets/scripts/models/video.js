ESF.Video = DS.Model.extend({
  user: DS.belongsTo('user', {
    async: true
  }),
  page: DS.belongsTo('page', {
    async: true
  }),
  video: DS.attr(),
  description: DS.attr(),
  weight: DS.attr('number'),
  thumbnail: DS.attr(),
  download: DS.attr()
});
