ESF.Router.map(function() {
  this.resource('page.add', {
    path: 'page/add'
  });
  this.resource('page', {
    path: 'page/:id'
  }, function() {
    this.route('delete');
    this.route('edit');
  });

  this.resource('user.forgot', {
    path: 'user/forgot'
  });
  this.resource('user.inactive', {
    path: 'user/inactive'
  });
  this.resource('user.login', {
    path: 'user/login'
  });
  this.resource('user.password', {
    path: 'user/password'
  });
  this.resource('user.register', {
    path: 'user/register'
  });
  this.resource('user.verify', {
    path: 'user/verify'
  });
  this.resource('user', {
    path: 'user/:id'
  }, function() {
    this.route('edit');
    this.route('instructors');
    this.route('pages');
    this.route('password');
    this.route('profile');
    this.route('subpages');
  });

  this.route('notFound', {
    path: '*:'
  });
});

ESF.Router.reopen({
  location: 'auto',

  track: function() {
    return ga('send', 'pageview', this.get('url'));
  }.on('didTransition')
});
