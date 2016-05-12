<?php

class Model {

  protected $id;

  public static function create($data) {
    $model = new static();

    foreach (static::$mapping['attributes'] as $key => $attribute) {
      if (isset($attribute['editable']) && !$attribute['editable']) {
        continue;
      }

      if (isset($attribute['required']) && $attribute['required'] && (!isset($data[$key]) || empty($data[$key]))) {
        throw new Exception('error.field.' . $key . '.required', 400);
      }
    }

    return $model->update($data)->persist();
  }

  public function delete() {
    try {
      Flight::get('orm.em')->remove($this);
    }
    catch (Exception $e) {
      throw new Exception('error.server');
    }
  }

  public function getId() {
    return $this->id;
  }

  public function generateId() {
    $this->id = mt_rand();
  }

  public static function index($filters = array()) {
    try {
      $models = Flight::get('orm.em')->getRepository(get_called_class())->findBy($filters);
    }
    catch (Exception $e) {
      throw new Exception('error.server');
    }

    return $models;
  }

  public function persist() {
    try {
      Flight::get('orm.em')->persist($this);
    }
    catch (Exception $e) {
      throw new Exception('error.server');
    }

    return $this;
  }

  public static function retrieve($model_id) {
    try {
      $model = Flight::get('orm.em')->getRepository(get_called_class())->find($model_id);
    }
    catch (Exception $e) {
      throw new Exception('error.server');
    }

    return $model;
  }

  public function serialize() {
    $model = array(
      'type' => static::$singular,
    );

    foreach (static::$mapping['attributes'] as $key => $attribute) {
      if (isset($attribute['readable']) && !$attribute['readable']) {
        continue;
      }

      if (isset($attribute['access'])) {
        $endpoint = get_called_class() . 'Endpoint';

        try {
          call_user_func(array($endpoint, 'access'), $attribute['access'], $this);
        }
        catch (Exception $e) {
          continue;
        }
      }

      if (!isset($attribute['format'])) {
        $attribute['format'] = 'default';
      }

      $method = 'get' . str_replace(' ', '', ucwords(implode(' ', explode('_', $key))));

      $model[$key] = call_user_func(array($this, $method));

      if (!is_null($model[$key])) {
        switch ($attribute['format']) {
          case 'date':
            $model[$key] = $model[$key]->format('d-m-Y');

            break;

          case 'datetime':
            $model[$key] = $model[$key]->format(DateTime::ATOM);

            break;
        }
      }
    }

    foreach (static::$mapping['associations'] as $key => $association) {
      $method = 'get' . str_replace(' ', '', ucwords(implode(' ', explode('_', $key))));

      if (!isset($association['bundle'])) {
        $association['bundle'] = FALSE;
      }

      switch ($association['type']) {
        case 'one':
          $associated = call_user_func(array($this, $method));

          if (empty($associated)) {
            $model[$key] = NULL;
          }
          else {
            if ($association['bundle']) {
              $model[$key] = $associated->serialize();
            }
            else {
              $model[$key] = $associated->getId();
            }
          }

          break;

        case 'many':
          $collection = call_user_func(array($this, $method));
          $model[$key] = array();

          if (empty($collection)) {
            break;
          }

          foreach ($collection as $associated) {
            if ($association['bundle']) {
              $model[$key][] = $associated->serialize();
            }
            else {
              $model[$key][] = $associated->getId();
            }
          }

          break;
      }
    }

    return (object) $model;
  }

  public function update($data) {
    foreach (static::$mapping['attributes'] as $key => $attribute) {
      if (isset($attribute['editable']) && !$attribute['editable']) {
        continue;
      }

      if (isset($attribute['access'])) {
        $endpoint = get_called_class() . 'Endpoint';

        try {
          call_user_func(array($endpoint, 'access'), $attribute['access']);
        }
        catch (Exception $e) {
          continue;
        }
      }

      $method = 'set' . str_replace(' ', '', ucwords(implode(' ', explode('_', $key))));

      if (isset($data[$key])) {
        call_user_func(array($this, $method), $data[$key]);
      }
    }

    foreach (static::$mapping['associations'] as $key => $association) {
      if (isset($association['editable']) && !$association['editable']) {
        continue;
      }

      $method = 'set' . str_replace(' ', '', ucwords(implode(' ', explode('_', $key))));

      if (isset($data[$key])) {
        call_user_func(array($this, $method), $data[$key]);
      }
    }

    return $this;
  }

  protected function _checkAssociation($field, $class, $models) {
    if (is_null($models)) {
      return;
    }

    $plural = is_array($models) && !count(array_filter(array_keys($models), 'is_string'));

    $candidates = $plural ? $models : array($models);
    $results = array();

    foreach ($candidates as $candidate) {
      if (is_object($candidate)) {
        $reflection = new ReflectionClass(get_class($candidate));

        if ($reflection->getShortName() != $class) {
          throw new Exception(sprintf('error.field.%s.invalid', $field), 400);
        }
      }
      elseif (is_array($candidate)) {
        if (!isset($candidate['id'])) {
          throw new Exception(sprintf('error.field.%s.invalid', $field), 400);
        }

        $model = call_user_func(array($class, 'retrieve'), $candidate['id']);

        if (is_null($model)) {
          throw new Exception(sprintf('error.field.%s.invalid', $field), 400);
        }

        try {
          call_user_func(array($class . 'Endpoint', 'access'), 'update', $model);
        }
        catch (Exception $e) {
          $results[] = $model;

          continue;
        }

        $candidate = $model->update($candidate);
      }
      else {
        $candidate = call_user_func(array($class, 'retrieve'), $candidate);

        if (is_null($candidate)) {
          throw new Exception(sprintf('error.field.%s.invalid', $field), 400);
        }
      }

      $results[] = $candidate;
    }

    return $plural ? $results : $results[0];
  }

}
