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

<?php $sort_custom = $this->configuration->getValue('get.sort_custom'); ?>
<?php if ($sort_custom): ?>
    $validators['sort_by'] = new sfValidatorChoice(array('choices' => <?php echo var_export($this->table->getColumnNames()) ?>, 'required' => false));
    $validators['sort_order'] = new sfValidatorChoice(array('choices' => array('asc', 'desc'), 'required' => false));
<?php endif; ?>

    foreach ($this->additional_params as $param)
    {
      $validators[$param] = new sfValidatorPass(array('required' => false));
    }

    return $validators;
  }
