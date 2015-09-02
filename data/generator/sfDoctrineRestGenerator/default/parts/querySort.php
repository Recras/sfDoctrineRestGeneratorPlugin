<?php $sort_custom = $this->configuration->getValue('get.sort_custom'); ?>
<?php $sort_default = $this->configuration->getValue('get.sort_default'); ?>
<?php if ($sort_default || $sort_custom): ?>
<?php
$columns = array();
foreach ($this->getColumns() as $column) {
	$columns[] = $column->getFieldName();
}
?>
  /**
   * Add sort clauses from "sort_default" and "sort_custom" fields
   *
   * @param  Doctrine_Query  $query   The query to add joins to
   * @param  array           &$params The filtered parameters for this request
   */
  function querySort(Doctrine_Query_Abstract $query, array &$params)
  {
    $columns = <?php echo var_export($columns, true); ?>;
<?php if ($sort_default && count($sort_default) == 2): ?>
    $sort = array('<?php echo $sort_default[0] ?>', '<?php echo $sort_default[1] ?>');
<?php else: ?>
    $sort = array();
<?php endif; ?>
<?php if ($sort_custom): ?>
    if (isset($params['sort_by']))
    {
      $sort[0] = $params['sort_by'];
      unset($params['sort_by']);

      if (isset($params['sort_order']))
      {
        $sort[1] = $params['sort_order'];
        unset($params['sort_order']);
      }
    }
<?php endif; ?>

    if ($sort)
    {
      if (in_array($sort[0], $columns)) {
        $sort[0] = $this->model . '.' . $sort[0];
      }
      $query->orderBy($sort[0] . ' ' . $sort[1]);
    }
  }
<?php else: ?>
  /* querySort function omitted, "sort_default" and "sort_custom" not set in
   * generator config
   */
<?php endif; ?>
