ESF.OAuth2Authenticator = SimpleAuth.Authenticators.OAuth2.extend({
  authenticate: function(credentials) {
    return new Ember.RSVP.Promise(function(resolve, reject) {
      Ember.$.ajax({
        url: '/api/token',
        type: 'POST',
        data: {
          grant_type: 'password',
          username: credentials.identification,
          password: credentials.password
        }
      }).then(function(response) {
        Ember.run(function() {
          resolve({
            access_token: response.access_token,
            user_active: response.user_active,
            user_id: response.user_id,
            user_role: response.user_role
          });
        });
      }, function(xhr, status, error) {
        Ember.run(function() {
          reject(xhr);
        });
      });
    });
  }
});
