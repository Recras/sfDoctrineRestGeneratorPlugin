<?php assert($this instanceof sfDoctrineRestGenerator); ?>
<?php
$pagination_custom_page_size = $this->configuration->getValue('get.pagination_custom_page_size');
$max_items = $this->configuration->getValue('get.max_items');
?>
  /**
   * Returns the list of pagination validators
   * @return  array  an array of validators
   */
  protected function getPaginationValidators()
  {
    $validators = array();
    $validators['page'] = new sfValidatorInteger(array('min' => 1, 'required' => false));
<?php if ($pagination_custom_page_size): ?>
    $validators['page_size'] = new sfValidatorInteger(array(
      'min' => 1,
<?php if ($max_items > 0): ?>
      'max' => <?php echo $max_items ?>,
<?php endif; ?>
      'required' => false
    ));
<?php endif; ?>
    return $validators;
  }
