<?php

class Page extends Model {

  protected $user;
  protected $comments;
  protected $logs;
  protected $photos;
  protected $videos;
  protected $instructors;
  protected $background;
  protected $created;
  protected $code;
  protected $title;
  protected $date;
  protected $body = '';
  protected $notes = '';
  protected $views = 0;
  protected $comments_allowed = TRUE;
  protected $photo_downloads_allowed = TRUE;
  protected $photo_mapping_allowed = TRUE;
  protected $sharing_allowed = TRUE;
  protected $video_downloads_allowed = TRUE;

  public static $singular = 'page';
  public static $plural = 'pages';

  public static $mapping = array(
    'attributes' => array(
      'id' => array(
        'editable' => FALSE,
      ),
      'created' => array(
        'editable' => FALSE,
        'format' => 'datetime',
      ),
      'code' => array(
        'editable' => FALSE,
      ),
      'title' => array(
        'required' => TRUE,
      ),
      'date' => array(),
      'body' => array(),
      'notes' => array(
        'access' => 'update',
      ),
      'views' => array(
        'editable' => FALSE,
      ),
      'comments_allowed' => array(),
      'photo_mapping_allowed' => array(),
      'sharing_allowed' => array(),
    ),
    'associations' => array(
      'user' => array(
        'bundle' => TRUE,
        'editable' => FALSE,
        'type' => 'one',
      ),
      'school' => array(
        'bundle' => TRUE,
        'editable' => FALSE,
        'type' => 'one',
      ),
      'comments' => array(
        'bundle' => TRUE,
        'editable' => FALSE,
        'type' => 'many',
      ),
      'logs' => array(
        'bundle' => TRUE,
        'type' => 'many',
      ),
      'photos' => array(
        'bundle' => TRUE,
        'type' => 'many',
      ),
      'videos' => array(
        'bundle' => TRUE,
        'type' => 'many',
      ),
      'instructors' => array(
        'bundle' => TRUE,
        'type' => 'many',
      ),
      'background' => array(
        'bundle' => TRUE,
        'editable' => TRUE,
        'type' => 'one',
      ),
    ),
    'filters' => array(
      'required' => TRUE,
      'fields' => array('code', 'school', 'user'),
    ),
  );

  public function __construct() {
    $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
    $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
    $this->instructors = new \Doctrine\Common\Collections\ArrayCollection();
  }

  public static function create($data) {
    $session = SessionHelper::getActiveSession();

    $model = parent::create($data);

    $model
      ->setCreated(new DateTime())
      ->setCode(static::_generateCode())
      ->setUser($session->getUser());

    return $model;
  }

  public function delete() {
    $this->setBackground(NULL);

    Flight::get('orm.em')->flush();

    parent::delete();
  }

  protected static function _generateCode() {
    for ($i = 0; $i < 900000; $i++) {
      $code = mt_rand(100000, 999999);

      $session = Flight::get('orm.em')->getRepository('Page')->findOneBy(array(
        'code' => $code,
      ));

      if (is_null($session)) {
        return $code;
      }
    }
  }

  public function getUser() {
    return $this->user;
  }

  public function getSchool() {
    if ($this->user) {
      return $this->user->getSchool();
    }

    return NULL;
  }

  public function getComments() {
    return $this->comments;
  }

  public function getLogs() {
    return $this->logs;
  }

  public function getPhotos() {
    return $this->photos;
  }

  public function getVideos() {
    return $this->videos;
  }

  public function getInstructors() {
    return $this->instructors;
  }

  public function getBackground() {
    return $this->background;
  }

  public function getCreated() {
    return $this->created;
  }

  public function getCode() {
    return $this->code;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getDate() {
    return $this->date;
  }

  public function getBody() {
    return $this->body;
  }

  public function getNotes() {
    return $this->notes;
  }

  public function getViews() {
    return $this->views;
  }

  public function getCommentsAllowed() {
    return $this->comments_allowed;
  }

  public function getPhotoMappingAllowed() {
    return $this->photo_mapping_allowed;
  }

  public function getSharingAllowed() {
    return $this->sharing_allowed;
  }

  public function incrementViews() {
    $session = Flight::session();

    if (!is_null($session)) {
      return $this;
    }

    $this->views++;

    return $this;
  }

  public function setUser($user) {
    $this->user = $this->_checkAssociation('user', 'User', $user);

    return $this;
  }

  public function setLogs($logs) {
    $logs = $this->_checkAssociation('logs', 'Log', $logs);

    foreach ($logs as $log) {
      $log->setPage($this);
    }

    if (empty($this->logs)) {
      $this->logs = $logs;
    }
    else {
      $this->logs->clear();

      foreach ($logs as $log) {
        $this->logs->add($log);
      }
    }

    return $this;
  }

  public function setPhotos($photos) {
    $photos = $this->_checkAssociation('photos', 'Photo', $photos);

    foreach ($photos as $photo) {
      $photo->setPage($this);
    }

    if (empty($this->photos)) {
      $this->photos = $photos;
    }
    else {
      $this->photos->clear();

      foreach ($photos as $photo) {
        $this->photos->add($photo);
      }
    }

    return $this;
  }

  public function setVideos($videos) {
    $videos = $this->_checkAssociation('videos', 'Video', $videos);

    foreach ($videos as $video) {
      $video->setPage($this);
    }

    if (empty($this->videos)) {
      $this->videos = $videos;
    }
    else {
      $this->videos->clear();

      foreach ($videos as $video) {
        $this->videos->add($video);
      }
    }

    return $this;
  }

  public function setInstructors($instructors) {
    $instructors = $this->_checkAssociation('instructors', 'User', $instructors);

    if (empty($this->instructors)) {
      $this->instructors = $instructors;
    }
    else {
      $this->instructors->clear();

      foreach ($instructors as $instructor) {
        $this->instructors->add($instructor);
      }
    }

    return $this;
  }

  public function setBackground($background) {
    $this->background = $this->_checkAssociation('background', 'Photo', $background);

    return $this;
  }

  public function setCreated($created) {
    $this->created = $created instanceof DateTime ? $created : new DateTime($created);

    return $this;
  }

  public function setCode($code) {
    $this->code = $code;

    return $this;
  }

  public function setTitle($title) {
    if (empty($title)) {
      throw new Exception('error.field.title.empty', 400);
    }

    $this->title = $title;

    return $this;
  }

  public function setDate($date) {
    $this->date = $date;

    return $this;
  }

  public function setBody($body) {
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', 'a[href],b,br,em,i,li,ol,p,strong,ul');

    $purifier = new HTMLPurifier($config);

    $this->body = $purifier->purify($body);

    return $this;
  }

  public function setNotes($notes) {
    $this->notes = $notes;

    return $this;
  }

  public function setViews($views) {
    $this->views = $views;

    return $this;
  }

  public function setCommentsAllowed($comments_allowed) {
    $this->comments_allowed = $comments_allowed;

    return $this;
  }

  public function setPhotoMappingAllowed($photo_mapping_allowed) {
    $this->photo_mapping_allowed = $photo_mapping_allowed;

    return $this;
  }

  public function setSharingAllowed($sharing_allowed) {
    $this->sharing_allowed = $sharing_allowed;

    return $this;
  }

}
