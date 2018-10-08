  /**
   * Format objects for output
   *
   * @param  array  $params  The request parameters
   * @return void
   */
  protected function formatObjects(array $params)
  {
<?php if (count($this->configuration->getValue('get.object_additional_fields')) > 0): ?>

    foreach ($this->objects as $key => $object)
    {
<?php   foreach ($this->configuration->getValue('get.object_additional_fields') as $idx => $field): ?>
<?php     if (is_numeric($idx)): ?>
      $this->embedAdditional<?php echo $field ?>($key, $params);
<?php       list($key, $function) = array(key($field), current($field)); ?>
<?php     else: ?>
      $this->objects[$key][<?php echo var_export($idx, true); ?>] = $this-><?php echo $field; ?>($object, $params);
<?php     endif; ?>
<?php   endforeach; ?>
    }
<?php endif; ?>
<?php foreach ($this->configuration->getValue('get.global_additional_fields') as $field): ?>
    $this->embedGlobalAdditional<?php echo $field ?>($params);
<?php endforeach; ?>

    // configure the fields of the returned objects and eventually hide some
    $this->setFieldVisibility();
    $this->configureFields();
  }
