ESF.Picture = DS.Model.extend({
  user: DS.belongsTo('user', {
    async: true
  }),
  logo: DS.attr(),
  display: DS.attr(),
  full: DS.attr(),
  scale: DS.attr('number'),
  angle: DS.attr('number'),
  x: DS.attr('number'),
  y: DS.attr('number')
});
