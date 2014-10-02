<?php $sort_custom = $this->configuration->getValue('get.sort_custom'); ?>
<?php $sort_default = $this->configuration->getValue('get.sort_default'); ?>
<?php if ($sort_custom || $sort_custom): ?>
  /**
   * Add sort clauses from "sort_default" and "sort_custom" fields
   *
   * @param  Doctrine_Query  $query   The query to add joins to
   * @param  array           &$params The filtered parameters for this request
   */
  function querySort(Doctrine_Query $query, array &$params)
  {
<?php if ($sort_default && count($sort_default) == 2): ?>
    $sort = '<?php echo $sort_default[0] ?> <?php echo $sort_default[1] ?>';
<?php endif; ?>
<?php if ($sort_custom): ?>
    if (isset($params['sort_by']))
    {
      $sort = $params['sort_by'];
      unset($params['sort_by']);

      if (isset($params['sort_order']))
      {
        $sort .= ' '.$params['sort_order'];
        unset($params['sort_order']);
      }
    }
<?php endif; ?>

    if (isset($sort))
    {
      $query->orderBy($sort);
    }
  }
<?php else: ?>
  /* querySort function omitted, "sort_default" and "sort_custom" not set in
   * generator config
   */
<?php endif; ?>
