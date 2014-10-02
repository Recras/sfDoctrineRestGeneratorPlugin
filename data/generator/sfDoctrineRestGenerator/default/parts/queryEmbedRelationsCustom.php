<?php $embed_relations_custom = $this->configuration->getValue('get.embed_relations_custom'); ?>
<?php if ($embed_relations_custom): ?>
  /**
   * Add joins for relations specified in the "embed_relations_custom" config
   *
   * @param  Doctrine_Query  $query   The query to add joins to
   * @param  array           $params  The filtered parameters for this request
   */
  public function queryEmbedRelationsCustom(Doctrine_Query $query, array &$params)
  {
    if (isset($params['embed']))
    {
        $embed_relations = explode('<?php echo $this->configuration->getValue('default.separator', ',') ?>', $params['embed']);

<?php foreach ($embed_relations_custom as $embed_relation): ?>
<?php if (!$this->isManyToManyRelation($embed_relation)): ?>
        if (in_array('<?php echo $embed_relation; ?>', $embed_relations))
        {
            $q->leftJoin($this->model.'.<?php echo $embed_relation ?> <?php echo $embed_relation ?>');
        }
<?php endif; ?>
<?php endforeach; ?>
        unset($params['embed']);
    }
  }
<?php else: ?>
  /** queryEmbedRelationsCustom omitted, "embed_relations_custom" config not set */
<?php endif; ?>
