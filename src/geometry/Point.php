<?php

namespace geometry;

class Point {

  protected $position;

  public $latitude;
  public $longitude;

  public function __construct($latitude, $longitude, $position) {
    $this->latitude = $latitude;
    $this->longitude = $longitude;
    $this->position = $position;
  }

  public function getPosition() {
    return $this->position;
  }

}
