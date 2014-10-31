<?php
$pagination_custom_page_size = $this->configuration->getValue('get.pagination_custom_page_size');
$pagination_default_page_size = $this->configuration->getValue('get.pagination_page_size');
$max_items = $this->configuration->getValue('get.max_items');
?>
  /**
   * Returns the list of pagination validators
   * @return  array  an array of validators
   */
  protected function getPaginationValidators()
  {
    $validators = array();
    $validators['page'] = new sfValidatorInteger(array(
      'min' => 1,
      'required' => false,
      'empty_value' => 1,
    ));
<?php if ($pagination_custom_page_size): ?>
    $validators['page_size'] = new sfValidatorInteger(array(
      'min' => 1,
<?php if ($max_items > 0): ?>
      'max' => <?php echo $max_items ?>,
<?php endif; ?>
      'required' => false,
      'empty_value' => <?php echo (int) $pagination_default_page_size; ?>,
    ));
<?php endif; ?>
    return $validators;
  }
