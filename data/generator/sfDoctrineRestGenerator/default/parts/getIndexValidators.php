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

    return $validators;
  }
