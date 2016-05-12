<?php

class Photo extends Model {

  protected $comment;
  protected $page;
  protected $user;
  protected $created;
  protected $data = array();
  protected $description;
  protected $file_path;
  protected $unapproved;
  protected $weight = 0;

  public static $singular = 'photo';
  public static $plural = 'photos';

  public static $accepted_formats = array(
    'image/jpeg' => '.jpg',
    'image/pjpeg' => '.jpg',
    'image/png' => '.png',
  );

  public static $mapping = array(
    'attributes' => array(
      'id' => array(
        'editable' => FALSE,
      ),
      'description' => array(),
      'unapproved' => array(),
      'weight' => array(),
      'thumbnail' => array(
        'editable' => FALSE,
      ),
      'display' => array(
        'editable' => FALSE,
      ),
      'full' => array(
        'editable' => FALSE,
      ),
      'latitude' => array(
        'editable' => FALSE,
      ),
      'longitude' => array(
        'editable' => FALSE,
      ),
    ),
    'associations' => array(
      'comment' => array(
        'editable' => FALSE,
        'type' => 'one',
      ),
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
    $directory = 'anonymous';
    $session = SessionHelper::getActiveSession();

    if (!empty($session) && !isset($data['user'])) {
      $data['user'] = $session->getUser();
    }

    if (isset($data['user'])) {
      $directory = $data['user']->getId();
    }

    $model = new static();

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($data['file_path']);

    if (!in_array($mime, array_keys(static::$accepted_formats))) {
      throw new Exception('error.file.invalid', 400);
    }

    $filename = uniqid() . static::$accepted_formats[$mime];
    $relative_folder = implode('/', array('files', $directory, 'photos'));
    $relative_path = $relative_folder . '/' . $filename;
    $absolute_folder = ROOT . '/' . $relative_folder;
    $absolute_path = $absolute_folder . '/' . $filename;

    if (!is_dir($absolute_folder)) {
      if (!mkdir($absolute_folder, 0755, TRUE)) {
        throw new Exception('error.server', 500);
      }
    }

    if (!copy($data['file_path'], $absolute_path)) {
      throw new Exception('error.server', 500);
    }

    $coordinates = ImageHelper::extractGPSCoordinates($relative_path);

    ImageHelper::orient($relative_path);

    $model
      ->setCreated(new DateTime())
      ->setData(array(
          'latitude' => $coordinates[0],
          'longitude' => $coordinates[1],
        ))
      ->setFilePath($relative_path)
      ->setUnapproved(TRUE);

    if (isset($data['user'])) {
      $model
        ->setUnapproved(FALSE)
        ->setUser($data['user']);
    }

    return $model->update($data)->persist();
  }

  public function destroyFiles() {
    array_map('unlink', glob(ROOT . '/' . substr($this->file_path, 0, -4) . '*'));
  }

  public function getComment() {
    return $this->comment;
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

  public function getData() {
    return $this->data;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getFilePath() {
    return $this->file_path;
  }

  public function getUnapproved() {
    return $this->unapproved;
  }

  public function getWeight() {
    return $this->weight;
  }

  public function getThumbnail() {
    return ImageHelper::getDerivativeURL($this->file_path, 'thumbnail');
  }

  public function getDisplay() {
    return ImageHelper::getDerivativeURL($this->file_path, 'display');
  }

  public function getFull() {
    return ImageHelper::getURL($this->file_path);
  }

  public function getLatitude() {
    return isset($this->data['latitude']) ? $this->data['latitude'] : NULL;
  }

  public function getLongitude() {
    return isset($this->data['longitude']) ? $this->data['longitude'] : NULL;
  }

  public function setComment($comment) {
    $this->comment = $this->_checkAssociation('comment', 'Comment', $comment);

    return $this;
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

  public function setData($data) {
    $this->data = $data;

    return $this;
  }

  public function setDescription($description) {
    $this->description = $description;

    return $this;
  }

  public function setUnapproved($unapproved) {
    $this->unapproved = $unapproved;

    return $this;
  }

  public function setWeight($weight) {
    $this->weight = $weight;

    return $this;
  }

  public function setFilePath($file_path) {
    $this->file_path = $file_path;

    return $this;
  }

}
