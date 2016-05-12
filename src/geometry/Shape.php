<?php

namespace geometry;

class Shape {

  protected $points = array();
  protected $sorted = TRUE;

  public function addPoint(Point $point) {
    $this->points[] = $point;
    $this->sorted = FALSE;

    return $this;
  }

  public function getPoints() {
    if (!$this->sorted) {
      usort($this->points, array(get_called_class(), 'sortPoints'));
      $this->sorted = TRUE;
    }

    return $this->points;
  }

  public static function sortPoints($a, $b) {
    if ($a->getPosition() < $b->getPosition()) return -1;
    if ($a->getPosition() > $b->getPosition()) return 1;

    return 0;
  }

}
