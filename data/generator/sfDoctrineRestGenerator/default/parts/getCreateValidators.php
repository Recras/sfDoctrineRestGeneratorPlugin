<?php assert($this instanceof sfDoctrineRestGenerator); ?>
  /**
   * Returns the list of validators for a create request.
   * @return  array<string,\sfValidatorBase>  an array of validators
   */
  public function getCreateValidators()
  {
    return <?php echo $this->getCreateValidatorsArray($this, 0, $this->configuration->getValue('create.disable_validators')); ?>;
  }
