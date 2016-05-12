<?php

class Comment extends Model {

  protected $page;
  protected $user;
  protected $photos;
  protected $created;
  protected $name;
  protected $body;

  public static $singular = 'comment';
  public static $plural = 'comments';

  public static $mapping = array(
    'attributes' => array(
      'id' => array(
        'editable' => FALSE,
      ),
      'created' => array(
        'editable' => FALSE,
        'format' => 'datetime',
      ),
      'name' => array(
        'required' => TRUE,
      ),
      'body' => array(
        'required' => TRUE,
      ),
    ),
    'associations' => array(
      'page' => array(
        'type' => 'one',
      ),
      'user' => array(
        'editable' => FALSE,
        'type' => 'one',
      ),
      'photos' => array(
        'bundle' => TRUE,
        'type' => 'many',
      ),
    ),
    'filters' => array(
      'required' => TRUE,
    ),
  );

  public static function create($data) {
    $session = SessionHelper::getActiveSession();

    $model = parent::create($data);

    $model->setCreated(new DateTime());

    if (!is_null($session)) {
      $model->setUser($session->getUser());
    }

    MailHelper::send(
      array($model->getPage()->getUser()->getMail()),
      'Nouveau commentaire sur votre page ESFBOOK',
      array(
        'comment',
        array(
          'title' => $model->getPage()->getTitle(),
          'name' => $model->getName(),
          'body' => $model->getBody(),
          'photos' => $model->getPhotos(),
        ),
      )
    );

    return $model;
  }

  public function getPage() {
    return $this->page;
  }

  public function getUser() {
    return $this->user;
  }

  public function getPhotos() {
    return $this->photos;
  }

  public function getCreated() {
    return $this->created;
  }

  public function getName() {
    return $this->name;
  }

  public function getBody() {
    return $this->body;
  }

  public function setPage($page) {
    $this->page = $this->_checkAssociation('page', 'Page', $page);

    return $this;
  }

  public function setUser($user) {
    $this->user = $this->_checkAssociation('user', 'User', $user);

    return $this;
  }

  public function setPhotos($photos) {
    $photos = $this->_checkAssociation('photos', 'Photo', $photos);

    foreach ($photos as $photo) {
      $photo->setComment($this);
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

  public function setCreated($created) {
    $this->created = $created instanceof DateTime ? $created : new DateTime($created);

    return $this;
  }

  public function setName($name) {
    $this->name = $name;

    return $this;
  }

  public function setBody($body) {
    $this->body = $body;

    return $this;
  }

}
