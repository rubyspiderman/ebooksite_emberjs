<?php

namespace geometry;

abstract class ShapeReducer {

  protected static function calculateDistance(Point $point, Point $start, Point $end) {
    return abs((
      $start->latitude * $end->longitude +
      $end->latitude * $point->longitude +
      $point->latitude * $start->longitude -
      $end->latitude * $start->longitude -
      $point->latitude * $end->longitude -
      $start->latitude * $point->longitude
    ) / 2) / sqrt(
      pow($start->latitude - $end->latitude, 2) +
      pow($start->longitude - $end->longitude, 2)
    ) * 2;
  }

  protected static function dp(Shape $old, Shape $new, $tolerance, $first, $last) {
    if ($last <= $first + 1) return;

    $points = $old->getPoints();

    $farthest = 0;
    $max_distance = 0.0;

    $first_point = $points[$first];
    $last_point = $points[$last];

    for ($i = $first; $i <= $last; $i++) {
      $point = $points[$i];

      $distance = static::calculateDistance($point, $first_point, $last_point);

      if ($distance > $max_distance) {
        $farthest = $i;
        $max_distance = $distance;
      }
    }

    if ($max_distance > $tolerance) {
      $new->addPoint($points[$farthest]);

      static::dp($old, $new, $tolerance, $first, $farthest);
      static::dp($old, $new, $tolerance, $farthest, $last);
    }
  }

  public static function reduce(Shape $shape, $tolerance) {
    $points = $shape->getPoints();

    if ($tolerance <= 0 || count($points) < 3) {
      return $shape;
    }

    $result = new Shape();

    $result->addPoint($points[0]);
    $result->addPoint($points[count($points) - 1]);

    static::dp($shape, $result, $tolerance, 0, count($points) - 1);

    return $result;
  }

}
