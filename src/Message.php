<?php

class Message extends Model {

  protected $id;
  protected $created;
  protected $active;
  protected $type;
  protected $en;
  protected $fr;

  public function setId($id) {
    $this->id = $id;

    return $this;
  }

  public function getId() {
    return $this->id;
  }

  public function setCreated($created) {
    $this->created = $created;

    return $this;
  }

  public function getCreated() {
    return $this->created;
  }

  public function setActive($active) {
    $this->active = $active;

    return $this;
  }

  public function getActive() {
    return $this->active;
  }

  public function setType($type) {
    $this->type = $type;

    return $this;
  }

  public function getType() {
    return $this->type;
  }

  public function setEn($en) {
    $this->en = $en;

    return $this;
  }

  public function getEn() {
    return $this->en;
  }

  public function setFr($fr) {
    $this->fr = $fr;

    return $this;
  }

  public function getFr() {
    return $this->fr;
  }

}
