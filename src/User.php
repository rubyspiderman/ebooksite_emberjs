<?php

class User extends Model {

  const ROLE_INSTRUCTOR = 1;
  const ROLE_SCHOOL = 2;
  const ROLE_ADMINISTRATOR = 3;

  protected $picture;
  protected $comments;
  protected $instructors;
  protected $logs;
  protected $pages;
  protected $photos;
  protected $sessions;
  protected $videos;
  protected $school;
  protected $created;
  protected $username;
  protected $password;
  protected $temporary_password;
  protected $role;
  protected $active = TRUE;
  protected $direct = FALSE;
  protected $first;
  protected $last;
  protected $name;
  protected $bio;
  protected $dob;
  protected $mail;
  protected $phone;
  protected $url;
  protected $booking;
  protected $facebook;
  protected $twitter;

  public static $singular = 'user';
  public static $plural = 'users';

  public static $mapping = array(
    'attributes' => array(
      'id' => array(
        'editable' => FALSE,
      ),
      'created' => array(
        'editable' => FALSE,
        'format' => 'datetime',
      ),
      'username' => array(
        'required' => TRUE,
      ),
      'password' => array(
        'readable' => FALSE,
      ),
      'role' => array(
        'editable' => FALSE,
      ),
      'active' => array(
        'editable' => FALSE,
      ),
      'direct' => array(
        'editable' => FALSE,
      ),
      'first' => array(
        'required' => TRUE,
      ),
      'last' => array(
        'required' => TRUE,
      ),
      'name' => array(),
      'bio' => array(),
      'dob' => array(
        'format' => 'date',
        'required' => TRUE,
      ),
      'mail' => array(
        'required' => TRUE,
      ),
      'phone' => array(),
      'url' => array(),
      'booking' => array(),
      'facebook' => array(),
      'twitter' => array(),
      'views' => array(
        'editable' => FALSE,
      ),
    ),
    'associations' => array(
      'picture' => array(
        'bundle' => TRUE,
        'type' => 'one',
      ),
      'instructors' => array(
        'editable' => FALSE,
        'type' => 'many',
      ),
      'pages' => array(
        'editable' => FALSE,
        'type' => 'many',
      ),
      'subpages' => array(
        'editable' => FALSE,
        'type' => 'many',
      ),
      'school' => array(
        'type' => 'one',
      ),
    ),
    'filters' => array(
      'required' => TRUE,
      'fields' => array('active', 'role', 'school'),
    ),
  );

  public function __construct() {
    $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    $this->instructors = new \Doctrine\Common\Collections\ArrayCollection();
    $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
    $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    $this->sessions = new \Doctrine\Common\Collections\ArrayCollection();
    $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
  }

  public static function create($data, $notify = TRUE) {
    $model = parent::create($data);

    $password = substr(md5(rand()), 0, 8);

    if (is_null($model->getSchool())) {
      throw new Exception('error.field.school.required', 400);
    }

    $model
      ->setCreated(new DateTime())
      ->setPassword($password)
      ->setRole(User::ROLE_INSTRUCTOR);

    if ($notify) {
      MailHelper::send(
        array($model->getMail()),
        'Détails de votre compte sur le site ESFBOOK',
        array(
          'welcome',
          array(
            'username' => $model->getUsername(),
            'password' => $password,
          ),
        )
      );
    }

    return $model;
  }

  public function getPicture() {
    return $this->picture;
  }

  public function getComments() {
    return $this->comments;
  }

  public function getInstructors() {
    return $this->instructors;
  }

  public function getPages() {
    return $this->pages;
  }

  public function getSubpages() {
    $subpages = new \Doctrine\Common\Collections\ArrayCollection();

    if ($this->getInstructors()) {
      foreach ($this->getInstructors() as $instructor) {
        if ($pages = $instructor->getPages()) {
          foreach ($pages as $page) {
            $subpages->add($page);
          }
        }
      }
    }

    return $subpages;
  }

  public function getSessions() {
    return $this->sessions;
  }

  public function getSchool() {
    return $this->school;
  }

  public function getCreated() {
    return $this->created;
  }

  public function getUsername() {
    return $this->username;
  }

  public function getPassword() {
    return $this->password;
  }

  public function getTemporaryPassword() {
    return $this->temporary_password;
  }

  public function getRole() {
    return $this->role;
  }

  public function getActive() {
    if ($this->getName() == 'Démo') {
      return TRUE;
    }

    if ($this->role == User::ROLE_INSTRUCTOR) {
      if (!$this->active) {
        return FALSE;
      }

      if ($this->getDirect()) {
        return TRUE;
      }

      $school = $this->getSchool();

      if (is_null($school)) {
        return FALSE;
      }

      return $school->getActive();
    }

    return $this->active;
  }

  public function getDirect() {
    return $this->direct;
  }

  public function getFirst() {
    return $this->first;
  }

  public function getLast() {
    return $this->last;
  }

  public function getName() {
    return $this->name;
  }

  public function getBio() {
    return $this->bio;
  }

  public function getDob() {
    return $this->dob;
  }

  public function getMail() {
    return $this->mail;
  }

  public function getPhone() {
    return $this->phone;
  }

  public function getUrl() {
    return $this->url;
  }

  public function getBooking() {
    return $this->booking;
  }

  public function getFacebook() {
    return $this->facebook;
  }

  public function getTwitter() {
    return $this->twitter;
  }

  public function getViews() {
    $count = 0;

    foreach ($this->pages as $page) {
      $count += $page->getViews();
    }

    return $count;
  }

  public function resetPassword() {
    $random = substr(md5(rand()), 0, 8);

    $this->temporary_password = md5($random);

    MailHelper::send(
      array($this->mail),
      'Renouvellement de votre mot de passe sur ESFBOOK',
      array(
        'forgot',
        array(
          'username' => $this->username,
          'password' => $random,
        ),
      )
    );

    return $this;
  }

  public function setPicture($picture) {
    $this->picture = $this->_checkAssociation('picture', 'Picture', $picture);

    $this->picture->setUser($this);

    return $this;
  }

  public function setSchool($school) {
    $this->school = $this->_checkAssociation('school', 'User', $school);

    return $this;
  }

  public function setCreated($created) {
    $this->created = $created;

    return $this;
  }

  public function setUsername($username) {
    if (empty($username)) {
      throw new Exception('error.field.username.empty', 400);
    }

    if ($username == $this->getUsername()) {
      return $this;
    }

    $user = Flight::get('orm.em')->getRepository('User')->findOneBy(array(
      'username' => $username,
    ));

    if (!is_null($user)) {
      throw new Exception('error.field.username.invalid', 400);
    }

    $this->username = $username;

    return $this;
  }

  public function setPassword($password) {
    if (!empty($password)) {
      $this->password = password_hash($password, PASSWORD_DEFAULT);
      $this->temporary_password = NULL;
    }

    return $this;
  }

  public function setTemporaryPassword($temporary_password) {
    $this->temporary_password = $temporary_password;

    return $this;
  }

  public function setRole($role) {
    $this->role = $role;

    return $this;
  }

  public function setActive($active) {
    $this->active = $active;

    return $this;
  }

  public function setDirect($direct) {
    $this->direct = $direct;

    return $this;
  }

  public function setFirst($first) {
    if (empty($first) && $this->getRole() == User::ROLE_INSTRUCTOR) {
      throw new Exception('error.field.first.empty', 400);
    }

    $this->first = $first;

    return $this;
  }

  public function setLast($last) {
    if (empty($last) && $this->getRole() == User::ROLE_INSTRUCTOR) {
      throw new Exception('error.field.last.empty', 400);
    }

    $this->last = $last;

    return $this;
  }

  public function setName($name) {
    if (empty($name) && $this->getRole() == User::ROLE_SCHOOL) {
      throw new Exception('error.field.name.empty', 400);
    }

    $this->name = $name;

    return $this;
  }

  public function setBio($bio) {
    $this->bio = $bio;

    return $this;
  }

  public function setDob($dob) {
    if (empty($dob)) {
      throw new Exception('error.field.dob.empty', 400);
    }

    $this->dob = new DateTime($dob);

    return $this;
  }

  public function setMail($mail) {
    if (empty($mail)) {
      throw new Exception('error.field.mail.empty', 400);
    }

    $this->mail = $mail;

    return $this;
  }

  public function setPhone($phone) {
    $this->phone = $phone;

    return $this;
  }

  public function setUrl($url) {
    $this->url = $url;

    return $this;
  }

  public function setBooking($booking) {
    $this->booking = $booking;

    return $this;
  }

  public function setFacebook($facebook) {
    $this->facebook = $facebook;

    return $this;
  }

  public function setTwitter($twitter) {
    $this->twitter = $twitter;

    return $this;
  }

}
