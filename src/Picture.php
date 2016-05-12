<?php

class Picture extends Model {

  protected $user;
  protected $angle = 0;
  protected $created;
  protected $file_path;
  protected $scale = 1.0;
  protected $x = 0;
  protected $y = 0;

  public static $singular = 'picture';
  public static $plural = 'pictures';

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
      'angle' => array(),
      'scale' => array(),
      'x' => array(),
      'y' => array(),
      'logo' => array(
        'editable' => FALSE,
      ),
      'display' => array(
        'editable' => FALSE,
      ),
      'full' => array(
        'editable' => FALSE,
      ),
    ),
    'associations' => array(
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
    $model = new static();

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($data['file_path']);

    if (!in_array($mime, array_keys(static::$accepted_formats))) {
      throw new Exception('error.file.invalid', 400);
    }

    $filename = uniqid() . static::$accepted_formats[$mime];
    $relative_folder = 'files/pictures';
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

    ImageHelper::orient($relative_path);

    $model
      ->setCreated(new DateTime())
      ->setFilePath($relative_path);

    return $model->update($data)->persist();
  }

  public function destroyFiles() {
    array_map('unlink', glob(ROOT . '/' . substr($this->file_path, 0, -4) . '*'));
  }

  public function getUser() {
    return $this->user;
  }

  public function getAngle() {
    return $this->angle;
  }

  public function getCreated() {
    return $this->created;
  }

  public function getFilePath() {
    return $this->file_path;
  }

  public function getScale() {
    return $this->scale;
  }

  public function getX() {
    return $this->x;
  }

  public function getY() {
    return $this->y;
  }

  public function getLogo() {
    return ImageHelper::getDerivativeURL($this->file_path, 'logo');
  }

  public function getDisplay() {
    return ImageHelper::getTransformedURL($this->file_path, $this->angle, $this->scale, $this->x, $this->y, 640, 640);
  }

  public function getFull() {
    return ImageHelper::getURL($this->file_path);
  }

  public function setUser($user) {
    if ($user == $this->user) {
      return $this;
    }

    $pictures = Flight::get('orm.em')->getRepository('Picture')->findBy(array(
      'user' => $user,
    ));

    foreach ($pictures as $picture) {
      Flight::get('orm.em')->remove($picture);
    }

    Flight::get('orm.em')->flush();

    $this->user = $this->_checkAssociation('user', 'User', $user);

    return $this;
  }

  public function setAngle($angle) {
    if ($angle % 90 != 0) {
      throw new Exception('error.field.angle.invalid');
    }

    $this->angle = $angle;

    return $this;
  }

  public function setCreated($created) {
    $this->created = $created instanceof DateTime ? $created : new DateTime($created);

    return $this;
  }

  public function setFilePath($file_path) {
    $this->file_path = $file_path;

    return $this;
  }

  public function setScale($scale) {
    $this->scale = $scale;

    return $this;
  }

  public function setX($x) {
    $this->x = $x;

    return $this;
  }

  public function setY($y) {
    $this->y = $y;

    return $this;
  }

}
