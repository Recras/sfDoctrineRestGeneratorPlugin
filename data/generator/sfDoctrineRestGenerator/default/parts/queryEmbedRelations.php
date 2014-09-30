<?php if ($embed_relations): ?>
  /**
   * Add joins for relations specified in the "embed_relations" config
   *
   * @param  Doctrine_Query  $query   The query to add joins to
   * @param  array           &$params The filtered parameters for this request
   */
  public function queryEmbedRelations(Doctrine_Query $query, array &$params)
  {
<?php foreach ($embed_relations as $embed_relation): ?>
<?php if (!$this->isManyToManyRelation($embed_relation)): ?>
    $query->leftJoin($this->model.'.<?php echo $embed_relation ?> <?php echo $embed_relation ?>');
<?php endif; ?>
<?php endforeach; ?>
  }
<?php else: ?>
  /** queryEmbedRelations omitted, "embed_relations" config not set */
<?php endif; ?>
