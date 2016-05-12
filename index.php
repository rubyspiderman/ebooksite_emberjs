<?php

define('ROOT', getcwd());

include ROOT . '/vendor/autoload.php';

Flight::map('error', array('ErrorEndpoint', 'exception'));
Flight::map('messages', array('SessionHelper', 'getActiveMessages'));
Flight::map('session', array('SessionHelper', 'getActiveSession'));
Flight::map('vimeo', array('VimeoHelper', 'getInstance'));

Flight::set('flight.views.path', 'views');
Flight::set('orm.em', ORMHelper::getEntityManager());

Flight::route('POST /api/token', array('OAuthEndpoint', 'token'));

Flight::route('POST /api/comments', array('CommentEndpoint', 'create'));
Flight::route('DELETE /api/comments/@id', array('CommentEndpoint', 'delete'));

Flight::route('GET /api/logs', array('LogEndpoint', 'index'));
Flight::route('POST /api/logs', array('LogEndpoint', 'create'));
Flight::route('GET /api/logs/@id', array('LogEndpoint', 'retrieve'));
Flight::route('PUT /api/logs/@id', array('LogEndpoint', 'update'));
Flight::route('DELETE /api/logs/@id', array('LogEndpoint', 'delete'));

Flight::route('GET /api/pages', array('PageEndpoint', 'index'));
Flight::route('POST /api/pages', array('PageEndpoint', 'create'));
Flight::route('GET /api/pages/@id', array('PageEndpoint', 'retrieve'));
Flight::route('PUT /api/pages/@id', array('PageEndpoint', 'update'));
Flight::route('DELETE /api/pages/@id', array('PageEndpoint', 'delete'));

Flight::route('GET /api/pictures', array('PictureEndpoint', 'index'));
Flight::route('POST /api/pictures', array('PictureEndpoint', 'create'));
Flight::route('GET /api/pictures/@id', array('PictureEndpoint', 'retrieve'));
Flight::route('PUT /api/pictures/@id', array('PictureEndpoint', 'update'));
Flight::route('DELETE /api/pictures/@id', array('PictureEndpoint', 'delete'));

Flight::route('GET /api/photos', array('PhotoEndpoint', 'index'));
Flight::route('POST /api/photos', array('PhotoEndpoint', 'create'));
Flight::route('GET /api/photos/@id', array('PhotoEndpoint', 'retrieve'));
Flight::route('PUT /api/photos/@id', array('PhotoEndpoint', 'update'));
Flight::route('DELETE /api/photos/@id', array('PhotoEndpoint', 'delete'));

Flight::route('GET /api/users', array('UserEndpoint', 'index'));
Flight::route('POST /api/users', array('UserEndpoint', 'create'));
Flight::route('GET /api/users/@id', array('UserEndpoint', 'retrieve'));
Flight::route('PUT /api/users/@id', array('UserEndpoint', 'update'));
Flight::route('DELETE /api/users/@id', array('UserEndpoint', 'delete'));
Flight::route('POST /api/users/forgot', array('UserEndpoint', 'forgot'));

Flight::route('GET /api/videos/ticket', array('VideoEndpoint', 'ticket'));
Flight::route('GET /api/videos', array('VideoEndpoint', 'index'));
Flight::route('POST /api/videos', array('VideoEndpoint', 'create'));
Flight::route('GET /api/videos/@id', array('VideoEndpoint', 'retrieve'));
Flight::route('PUT /api/videos/@id', array('VideoEndpoint', 'update'));
Flight::route('DELETE /api/videos/@id', array('VideoEndpoint', 'delete'));

Flight::route('GET /api/cron', array('CronEndpoint', 'run'));

Flight::route('/api', function() {
  throw new Exception('error.resource.not_found', 404);
});

Flight::route('/api/*', function() {
  throw new Exception('error.resource.not_found', 404);
});

Flight::route('/en/*', function() {
  Flight::render('en', array(
    'messages' => JSONHelper::encode((object) Flight::messages('en')),
  ));
});

Flight::route('/*', function() {
  Flight::render('fr', array(
    'messages' => JSONHelper::encode((object) Flight::messages('fr')),
  ));
});

Flight::before('start', function() {
  $session = Flight::session();

  if (!is_null($session)) {
    $session->setLastAccess(new DateTime());
    Flight::get('orm.em')->flush();
  }
});

Flight::start();
