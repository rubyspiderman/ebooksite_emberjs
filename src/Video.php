<?php

class Video extends Model {

  protected $page;
  protected $user;
  protected $created;
  protected $refreshed;
  protected $data = array();
  protected $description;
  protected $vimeo_id;
  protected $weight = 0;

  public static $singular = 'video';
  public static $plural = 'videos';

  public static $mapping = array(
    'attributes' => array(
      'id' => array(
        'editable' => FALSE,
      ),
      'video' => array(
        'editable' => FALSE,
      ),
      'description' => array(),
      'weight' => array(),
      'thumbnail' => array(
        'editable' => FALSE,
      ),
      'download' => array(
        'editable' => FALSE,
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
    ),
    'filters' => array(
      'required' => TRUE,
    ),
  );

  public static function create($data) {
    $session = SessionHelper::getActiveSession();

    if (!empty($session) && !isset($data['user'])) {
      $data['user'] = $session->getUser();
    }

    $model = new static();

    $model
      ->setCreated(new DateTime())
      ->setUser($data['user'])
      ->setVimeoId($data['vimeo_id']);

    return $model->update($data)->persist();
  }

  public function destroyFiles() {
    Flight::vimeo()->request('/videos/' . $this->vimeo_id, array(), 'DELETE');
  }

  public function getPage() {
    return $this->page;
  }

  public function getUser() {
    return $this->user;
  }

  public function getCreated() {
    return $this->created;
  }

  public function getRefreshed() {
    return $this->refreshed;
  }

  public function getData() {
    return $this->data;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getVimeoId() {
    return $this->vimeo_id;
  }

  public function getWeight() {
    return $this->weight;
  }

  public function getVideo() {
    return '//player.vimeo.com/video/' . $this->vimeo_id;
  }

  public function getThumbnail() {
    if (isset($this->data['pictures']['sizes'])) {
      foreach ($this->data['pictures']['sizes'] as $size) {
        if ($size['width'] == 640) {
          return $size['link'];
        }
      }
    }

    return '/images/video-placeholder.png';
  }

  public function getDownload() {
    if (isset($this->data['download']) && count($this->data['download'])) {
      return $this->data['download'][0]['link'];
    }

    return NULL;
  }

  public function setPage($page) {
    $this->page = $this->_checkAssociation('page', 'Page', $page);

    return $this;
  }

  public function setUser($user) {
    $this->user = $this->_checkAssociation('user', 'User', $user);

    return $this;
  }

  public function setCreated($created) {
    $this->created = $created instanceof DateTime ? $created : new DateTime($created);

    return $this;
  }

  public function setRefreshed($refreshed) {
    $this->refreshed = $refreshed instanceof DateTime ? $refreshed : new DateTime($refreshed);

    return $this;
  }

  public function setData($data) {
    $this->data = $data;

    return $this;
  }

  public function setDescription($description) {
    $this->description = $description;

    return $this;
  }

  public function setVimeoId($vimeo_id) {
    $request = Flight::vimeo()->request('/videos/' . $vimeo_id);

    if ($request['status'] != 200) {
      throw new Exception('error.vimeo.invalid', 400);
    }

    $this->data = $request['body'];
    $this->vimeo_id = $vimeo_id;

    return $this;
  }

  public function setWeight($weight) {
    $this->weight = $weight;

    return $this;
  }

}
