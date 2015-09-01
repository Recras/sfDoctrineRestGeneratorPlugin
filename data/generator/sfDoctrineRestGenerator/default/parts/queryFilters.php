  /**
   * Filter fields
   *
   * @param  Doctrine_Query  $query   The query to add joins to
   * @param  array           &$params The filtered parameters for this request
   */
  function queryFilters(Doctrine_Query_Abstract $q, array &$params)
  {
<?php $filters = $this->configuration->getFilters() ?>
<?php foreach ($filters as $name => $filter): ?>
<?php if (isset($filters[$name]['multiple']) && $filters[$name]['multiple']): ?>
    if (isset($params['<?php echo $name ?>']))
    {
      $values = explode('<?php echo $this->configuration->getValue('default.separator', ',') ?>', $params['<?php echo $name ?>']);

      if (count($values) == 1)
      {
        $q->andWhere($this->model.'.<?php echo $name ?> = ?', $values[0]);
      }
      else
      {
        $q->whereIn($this->model.'.<?php echo $name ?>', $values);
      }

      unset($params['<?php echo $name ?>']);
    }
<?php endif; ?>
<?php if (isset($filters[$name]['compare'])): ?>
<?php $operators = array(
  'less' => '<',
  'lessEqual' => '<=',
  'greater' => '>',
  'greaterEqual' => '>=',
); ?>
<?php foreach ($filters[$name]['compare'] as $operation => $opAlias): ?>
<?php $opAlias = $opAlias == null ? $operation : $opAlias; ?>
    if (isset($params['<?php echo $name . ucfirst($opAlias) ?>']))
    {
      $q->andWhere($this->model.'.<?php echo $name ?> <?php echo $operators[$operation] ?> ?', $params['<?php echo $name . ucfirst($opAlias) ?>']);
      unset($params['<?php echo $name . ucfirst($opAlias) ?>']);
    }
<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>
  }
