<?php

class Session extends Model {

  protected $user;
  protected $opened;
  protected $last_access;
  protected $closed;
  protected $token;
  protected $ip;
  protected $ua;

  public static $singular = 'session';
  public static $plural = 'sessions';

  public static $mapping = array(
    'attributes' => array(
      'id' => array(
        'editable' => FALSE,
      ),
      'opened' => array(
        'format' => 'datetime',
        'required' => TRUE,
      ),
      'token' => array(
        'readable' => FALSE,
      ),
      'ip' => array(),
      'ua' => array(),
    ),
    'associations' => array(
      'user' => array(
        'type' => 'one',
      ),
    ),
  );

  public function getUser() {
    return $this->user;
  }

  public function getOpened() {
    return $this->opened;
  }

  public function getLastAccess() {
    return $this->last_access;
  }

  public function getClosed() {
    return $this->closed;
  }

  public function getToken() {
    return $this->token;
  }

  public function getIp() {
    return $this->ip;
  }

  public function getUa() {
    return $this->ua;
  }

  public function setUser(User $user) {
    $this->user = $user;

    return $this;
  }

  public function setOpened($opened) {
    $this->opened = $opened;

    return $this;
  }

  public function setLastAccess($last_access) {
    $this->last_access = $last_access;

    return $this;
  }

  public function setClosed($closed) {
    $this->closed = $closed;

    return $this;
  }

  public function setToken($token) {
    $this->token = $token;

    return $this;
  }

  public function setIp($ip) {
    $this->ip = $ip;

    return $this;
  }

  public function setUa($ua) {
    $this->ua = $ua;

    return $this;
  }

}
