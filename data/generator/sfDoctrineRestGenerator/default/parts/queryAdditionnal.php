<?php assert($this instanceof sfDoctrineRestGenerator); ?>
<?php $object_additional_fields = $this->configuration->getValue('get.object_additional_fields'); ?>
<?php foreach ($object_additional_fields as $field): ?>

  /**
   * Allows to embed an additional field "<?php echo $field ?>" in each item
   * of the resultset
   *
   * @param   $item    the index of an item in the resultset
   * @param   $params  the filtered params of the request
   */
//  public function embedAdditional<?php echo $field ?>($item, $params)
//  {
//    $array = $this->objects[$item];
//    put your code here, in order to change the content of $array
//    ...
//    $this->objects[$item] = $array;
//  }
<?php endforeach; ?><?php $global_additional_fields = $this->configuration->getValue('get.global_additional_fields'); ?>
<?php foreach ($global_additional_fields as $field): ?>

  /**
   * Allows to embed an additional field "<?php echo $field ?>" at the root
   * level of the resultset
   *
   * @param   $items   the whole resultset
   * @param   $params  the filtered params of the request
   */
//  public function embedGlobalAdditional<?php echo $field ?>($params)
//  {
//    $array = $this->objects;
//    put your code here, in order to change the content of $array
//    ...
//    $this->objects = $array;
//  }
<?php endforeach; ?>
