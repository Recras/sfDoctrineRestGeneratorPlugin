  protected function embedManyToManyRelations($params)
  {
  <?php $embed_relations = $this->configuration->getValue('get.embed_relations'); ?>
  <?php foreach ($embed_relations as $embed_relation): ?>
  <?php if ($this->isManyToManyRelation($embed_relation)): ?>
    $this->embedManyToMany<?php echo $embed_relation ?>($params);
  <?php endif; ?><?php endforeach; ?>
  }
