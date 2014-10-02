  /**
   * Create the query for selecting objects, eventually along with related
   * objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function query($params)
  {
    $q = Doctrine_Query::create()
<?php
$display = $this->configuration->getValue('get.display');

$fields = $display;
foreach ($embed_relations as $relation_name)
{
  $fields[] = $relation_name.'.*';
}
?>
<?php if (count($display) > 0): ?>
<?php $display = implode(', ', $fields); ?>
      ->select('<?php echo $display ?>')
<?php endif; ?>

      ->from($this->model.' '.$this->model);

    $this->queryEmbedRelations($q, $params);
    $this->queryEmbedRelationsCustom($q, $params);

    $this->queryPagination($q, $params);
    $this->querySort($q, $params);

    $this->queryFilterPrimaryKeys($q, $params);

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
    foreach ($params as $name => $value)
    {
      $q->andWhere($this->model.'.'.$name.' = ?', $value);
    }

    return $q;
  }
