  /**
   * Returns the list of validators for a get request.
   * @return  array  an array of validators
   */
  public function getIndexValidators()
  {
  	$validators = array();
<?php foreach ($this->getColumns() as $column): ?>
    $validators['<?php echo $column->getFieldName() ?>'] = new <?php echo $this->getIndexValidatorClassForColumn($column) ?>(<?php echo $this->getIndexValidatorOptionsForColumn($column) ?>);
<?php endforeach; ?>

    $validators = array_merge($validators, $this->getPaginationValidators());

    $validators = array_merge($validators, $this->getSortValidators());

    foreach ($this->additional_params as $param)
    {
      $validators[$param] = new sfValidatorPass(array('required' => false));
    }
<?php $embed_relations_custom = $this->configuration->getValue('get.embed_relations_custom'); ?>
<?php if ($embed_relations_custom): ?>
    $validators['embed'] = new sfValidatorCallback(array(
        'required' => false,
        'callback' => function ($validator, $input) {
            foreach (explode('<?php echo $this->configuration->getValue('default.separator'); ?>', $input) as $embed) {
                if (!in_array($embed, <?php echo var_export($embed_relations_custom); ?>)) {
                    throw new sfValidatorError($validator, '"' . $embed . '" is not embeddable');
                }
            }
            return $input;
        },
    ));
<?php endif; ?>

<?php $filters = $this->configuration->getValue('get.filters'); ?>
<?php if ($filters): ?>
<?php   foreach ($filters as $name => $filter): ?>
<?php     if (isset($filter['compare'])): ?>
<?php       foreach ($filter['compare'] as $operator => $opAlias) : ?>
<?php         $opAlias = ucfirst($opAlias == null ? $operator : $opAlias); ?>
    $validators['<?php echo $name . $opAlias ?>'] = new sfValidatorPass(array(
      'required' => false,
    ));
<?php       endforeach; ?>
<?php     endif; ?>
<?php   endforeach; ?>
<?php endif; ?>

    return $validators;
  }
