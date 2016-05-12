window.ENV = window.ENV || {};
window.ENV['simple-auth'] = {
  authenticationRoute: 'user.login',
  authorizer: 'simple-auth-authorizer:oauth2-bearer',
  routeAfterAuthentication: 'user',
  store: 'simple-auth-session-store:cookie'
};
window.ENV['simple-auth-cookie-store'] = {
  cookieDomain: '.esfbook.net',
  cookieExpirationTime: 60 * 60 * 24 * 30,
  cookieName: 'esfbook:session'
};

window.ESF = Ember.Application.create();

Ember.Application.initializer({
  name: 'authentication',
  before: 'simple-auth',
  initialize: function(container, application) {
    container.register('authenticator:oauth2', ESF.OAuth2Authenticator);
  }
});

SimpleAuth.Session.reopen({
  isActive: function() {
    return this.get('user_active');
  }.property('user_active'),
  isInstructor: function() {
    return this.get('user_role') == 1;
  }.property('user_role'),
  isSchool: function() {
    return this.get('user_role') == 2;
  }.property('user_role'),
  isAdmin: function() {
    return this.get('user_role') == 3;
  }.property('user_role')
});

ESF.ApplicationAdapter = DS.ActiveModelAdapter.extend({
  namespace: 'api'
});
