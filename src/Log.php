<?php

class Log extends Model {

  protected $page;
  protected $user;
  protected $created;
  protected $data = array();
  protected $description;
  protected $file_path;
  protected $weight = 0;

  public static $singular = 'log';
  public static $plural = 'logs';

  public static $mapping = array(
    'attributes' => array(
      'id' => array(
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
      'paths' => array(
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

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($data['file_path']);

    if ($mime != 'application/xml') {
      throw new Exception('error.file.invalid', 400);
    }

    $filename = uniqid() . '.' . GPSHelper::determineExtension($data['file_path']);
    $relative_folder = implode('/', array('files', $data['user']->getId(), 'logs'));
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

    $model
      ->setCreated(new DateTime())
      ->setData(array(
          'paths' => GPSHelper::extractPaths($relative_path),
        ))
      ->setFilePath($relative_path)
      ->setUser($data['user']);

    return $model->update($data)->persist();
  }

  public function destroyFiles() {
    unlink(ROOT . '/' . $this->file_path);
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

  public function getWeight() {
    return $this->weight;
  }

  public function getThumbnail() {
    return '/images/log-placeholder.png';
  }

  public function getFilePath() {
    return $this->file_path;
  }

  public function getDownload() {
    return GPSHelper::getURL($this->file_path);
  }

  public function getPaths() {
    return isset($this->data['paths']) ? $this->data['paths'] : array();
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

  public function setWeight($weight) {
    $this->weight = $weight;

    return $this;
  }

  public function setFilePath($file_path) {
    $this->file_path = $file_path;

    return $this;
  }

}
