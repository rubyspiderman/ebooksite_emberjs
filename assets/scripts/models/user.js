ESF.User = DS.Model.extend({
  created: DS.attr(),
  username: DS.attr(),
  password: DS.attr(),
  role: DS.attr(),
  active: DS.attr('boolean', {
    defaultValue: true
  }),
  direct: DS.attr('boolean', {
    defaultValue: false
  }),
  instructors: DS.hasMany('user', {
    async: true,
    inverse: 'school'
  }),
  school: DS.belongsTo('user', {
    async: true,
    inverse: 'instructors'
  }),
  first: DS.attr(),
  last: DS.attr(),
  name: DS.attr(),
  bio: DS.attr(),
  dob: DS.attr(),
  mail: DS.attr(),
  phone: DS.attr(),
  facebook: DS.attr(),
  twitter: DS.attr(),
  url: DS.attr(),
  booking: DS.attr(),
  views: DS.attr(),
  picture: DS.belongsTo('picture'),
  pages: DS.hasMany('pages', {
    async: true,
    inverse: 'user'
  }),
  subpages: DS.hasMany('pages', {
    async: true,
    inverse: 'school'
  }),
  display: function() {
    if (this.get('name')) {
      return this.get('name');
    }
    else if (this.get('first') && this.get('last')) {
      return this.get('first') + ' ' + this.get('last');
    }
    else {
      return this.get('username');
    }
  }.property('first', 'last', 'name'),
  emailLink: function() {
    return 'mailto:' + this.get('mail');
  }.property('mail'),
  facebookLink: function() {
    return this.get('facebook');
  }.property('facebook'),
  isInstructor: function() {
    return this.get('role') == 1 || this.get('role') == 3;
  }.property('role'),
  isSchool: function() {
    return this.get('role') == 2;
  }.property('role'),
  twitterLink: function() {
    return 'https://twitter.com/' + this.get('twitter');
  }.property('twitter')
});
