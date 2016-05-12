<?php

use \Doctrine\Common\Collections\Criteria;

abstract class CronEndpoint extends Endpoint {

  public static function run() {
    $results = array(
      'videos_failed' => array(),
      'videos_refreshed' => array(),
      'videos_removed' => array(),
    );

    $start = time();
    $max = ini_get('max_execution_time') - 1;

    $criteria = Criteria::create()
      ->where(Criteria::expr()->isNull('refreshed'))
      ->orWhere(Criteria::expr()->lt('refreshed', new DateTime('-5 minutes')))
      ->orderBy(array('refreshed' => Criteria::ASC));

    $videos = Flight::get('orm.em')->getRepository('Video')->matching($criteria);

    foreach ($videos as $video) {
      if (time() - $start > $max) {
        break;
      }

      $request = Flight::vimeo()->request('/videos/' . $video->getVimeoId());

      if ($request['status'] == 404) {
        $results['videos_removed'][] = array(
          'id' => $video->getId(),
        );

        Flight::get('orm.em')->remove($video);

        continue;
      }
      elseif ($request['status'] != 200) {
        $results['videos_failed'][] = array(
          'id' => $video->getId(),
          'vimeo_id' => $video->getVimeoId(),
          'status' => $request['status'],
        );

        continue;
      }

      $video->setData($request['body']);
      $video->setRefreshed(new DateTime());

      $results['videos_refreshed'][] = array(
        'id' => $video->getId(),
        'vimeo_id' => $video->getVimeoId(),
      );
    }

    static::send($results);
  }

}
