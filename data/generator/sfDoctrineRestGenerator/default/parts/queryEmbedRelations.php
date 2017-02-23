<?php $embed_relations = $this->configuration->getValue('get.embed_relations'); ?>
<?php $embed_relations_display = $this->configuration->getValue('get.embed_relations_display'); ?>
<?php $display = $this->configuration->getValue('get.display'); ?>
<?php if ($embed_relations): ?>
  /**
   * Add joins for relations specified in the "embed_relations" config
   *
   * @param  Doctrine_Query  $query   The query to add joins to
   * @param  array           &$params The filtered parameters for this request
   */
  public function queryEmbedRelations(Doctrine_Query_Abstract $query, array &$params)
  {
<?php foreach ($embed_relations as $embed_relation): ?>
    $query->leftJoin($this->model.'.<?php echo $embed_relation ?> <?php echo $embed_relation ?>');
<?php if ($display): ?>
<?php if (isset($embed_relations_display[$embed_relation])): ?>
<?php foreach ($embed_relations_display[$embed_relation] as $field): ?>
    $query->addSelect('<?php echo $embed_relation . '.' . $field; ?>');
<?php endforeach; ?>
<?php else: ?>
    $query->addSelect('<?php echo $embed_relation; ?>.*');
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
  }
<?php else: ?>
  /** queryEmbedRelations omitted, "embed_relations" config not set */
<?php endif; ?>
