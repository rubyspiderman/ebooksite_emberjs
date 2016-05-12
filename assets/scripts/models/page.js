ESF.Page = DS.Model.extend({
  user: DS.belongsTo('user', {
    inverse: 'pages'
  }),
  school: DS.belongsTo('user', {
    inverse: 'subpages'
  }),
  instructors: DS.hasMany('user', {
    inverse: null
  }),
  created: DS.attr('date'),
  code: DS.attr('number'),
  title: DS.attr(),
  date: DS.attr('string', {
    defaultValue: function() {
      return moment().format('DD-MM-YYYY');
    }
  }),
  body: DS.attr(),
  notes: DS.attr(),
  photos: DS.hasMany('photo'),
  videos: DS.hasMany('video'),
  logs: DS.hasMany('log'),
  comments: DS.hasMany('comment'),
  background: DS.belongsTo('photo'),
  views: DS.attr('number'),
  commentsAllowed: DS.attr('boolean', {
    defaultValue: true
  }),
  sharingAllowed: DS.attr('boolean', {
    defaultValue: true
  }),
  photoMappingAllowed: DS.attr('boolean', {
    defaultValue: true
  }),
  hasInstructors: function() {
    return this.get('user.isInstructor') || !!this.get('instructors.length');
  }.property('user', 'instructors')
});
