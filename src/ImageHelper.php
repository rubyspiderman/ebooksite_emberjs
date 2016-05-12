<?php

use WideImage\WideImage;

abstract class ImageHelper {

  public static function extractGPSCoordinates($file) {
    $exif = @exif_read_data(ROOT . '/' . $file);

    if (!(isset($exif['GPSLatitude']) && isset($exif['GPSLongitude']))) {
      return array(NULL, NULL);
    }

    $latitude = static::exifGPSToFloat($exif['GPSLatitude'], $exif['GPSLatitudeRef']);
    $longitude = static::exifGPSToFloat($exif['GPSLongitude'], $exif['GPSLongitudeRef']);

    return array($latitude, $longitude);
  }

  public static function getDerivativeURL($file, $style) {
    $base = substr($file, 0, -4);
    $extension = substr($file, -4);

    $derivative = $base . '.' . $style . $extension;
    $path = ROOT . '/' . $derivative;

    if (file_exists($path)) {
      return static::getURL($derivative);
    }

    $image = WideImage::load(ROOT . '/' . $file);

    switch ($style) {
      case 'logo':
        $image
          ->resize(150, 150, 'inside')
          ->saveToFile($path);
          break;
      case 'thumbnail':
        $image
          ->resize(640, 640, 'outside')
          ->resizeCanvas(640, 480, 'center', 'center')
          ->saveToFile($path);
          break;
      case 'display':
        $image
          ->resizeDown(960, 960)
          ->saveToFile($path);
        break;
      default:
        throw new Exception('error.server', 500);
    }

    return static::getURL($derivative);
  }

  public static function getTransformedURL($file, $angle, $scale, $x, $y, $width, $height) {
    $base = substr($file, 0, -4);
    $extension = substr($file, -4);

    $derivative = $base . '.' . substr(md5(implode(':', array($file, $angle, $scale, $x, $y, $width, $height))), 0, 13) . $extension;
    $path = ROOT . '/' . $derivative;

    if (file_exists($path)) {
      return static::getURL($derivative);
    }

    $image = WideImage::load(ROOT . '/' . $file);

    $image
      ->rotate($angle)
      ->resize($image->getWidth() * $scale)
      ->crop($x, $y, $width, $height)
      ->saveToFile($derivative);

    return static::getURL($derivative);
  }

  public static function getURL($file) {
    return '/' . $file;
  }

  public static function orient($file) {
    $file = ROOT . '/' . $file;

    $exif = @exif_read_data($file);
    $orientation = isset($exif['Orientation']) ? $exif['Orientation'] : NULL;
    $image = WideImage::load($file);

    switch ($orientation) {
      case 2:
        $image->mirror()->saveToFile($file);
        break;

      case 3:
        $image->rotate(180)->saveToFile($file);
        break;

      case 4:
        $image->rotate(180)->mirror()->saveToFile($file);
        break;

      case 5:
        $image->rotate(90)->mirror()->saveToFile($file);
        break;

      case 6:
        $image->rotate(90)->saveToFile($file);
        break;

      case 7:
        $image->rotate(-90)->mirror()->saveToFile($file);
        break;

      case 8:
        $image->rotate(-90)->saveToFile($file);
        break;

      default:
        $image->saveToFile($file);
    }
  }

  private static function exifGPSToFloat($coordinate, $hemisphere) {
    $degrees = count($coordinate) > 0 ? static::mixedToFloat($coordinate[0]) : 0;
    $minutes = count($coordinate) > 1 ? static::mixedToFloat($coordinate[1]) : 0;
    $seconds = count($coordinate) > 2 ? static::mixedToFloat($coordinate[2]) : 0;

    $flip = ($hemisphere == 'S' || $hemisphere == 'W') ? -1 : 1;

    return ($degrees + $minutes / 60 + $seconds / 3600) * $flip;
  }

  private static function mixedToFloat($part) {
    $parts = explode('/', $part);

    if (!count($parts)) {
      return 0;
    }

    if (count($parts) == 1) {
      return $parts[0];
    }

    return floatval($parts[0]) / floatval($parts[1]);
  }

}
