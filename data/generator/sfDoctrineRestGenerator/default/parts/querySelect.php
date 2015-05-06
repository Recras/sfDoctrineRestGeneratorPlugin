  /**
   * Add Select clause to query
   *
   * @param  Doctrine_Query  $query   The query to add joins to
   * @param  array           &$params The filtered parameters for this request
   */
  function querySelect(Doctrine_Query $q, array &$params)
  {
<?php
$display = $this->configuration->getValue('get.display');

$fields = $display;
?>
<?php if (count($display) > 0): ?>
<?php $display = implode(', ', $fields); ?>
    $q->select('<?php echo $display ?>');
<?php endif; ?>
  }
