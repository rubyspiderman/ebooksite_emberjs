<?php

use geoPHP as Geo;

use geometry\Point;
use geometry\Shape;
use geometry\ShapeReducer;

abstract class GPSHelper {

  public static function determineExtension($path) {
    $data = strtolower(file_get_contents($path));

    if (strpos($data, '<kml') !== FALSE) {
      return 'kml';
    }

    if (strpos($data, '<gpx') !== FALSE) {
      return 'gpx';
    }

    throw new Exception('error.file.unsupported');
  }

  public static function extractPaths($file) {
    $linestrings = array();
    $paths = array();

    $data = file_get_contents(ROOT . '/' . $file);

    switch (strtolower(substr($file, -3))) {
      case 'gpx':
        $data = Geo::load($data, 'gpx');
        break;

      case 'kml':
        $data = Geo::load($data, 'kml');
        break;

      default:
        return $paths;
    }

    $data = JSONHelper::decode($data->out('json'));

    if ($data['type'] == 'LineString') {
      $linestrings[] = $data['coordinates'];
    }

    if ($data['type'] == 'GeometryCollection') {
      foreach ($data['geometries'] as $geometry) {
        if ($geometry['type'] == 'LineString') {
          $linestrings[] = $geometry['coordinates'];
        }
      }
    }

    foreach ($linestrings as $key => $linestring) {
      $polyline = new Shape();

      for ($i = 0; $i < count($linestring); $i++) {
        $polyline->addPoint(new Point($linestring[$i][1], $linestring[$i][0], $i));
      }

      $c = 0.0002;

      $paths[] = ShapeReducer::reduce($polyline, $c)->getPoints();
    }

    return $paths;
  }

  public static function getURL($file) {
    return '/' . $file;
  }

}
