/**
 * Returns the list of sort validators
 * @return  array  an array of validators
 */
protected function getSortValidators()
{
  $validators['sort_by'] = new sfValidatorChoice(array(
    'choices' => <?php echo var_export($this->table->getColumnNames()) ?>,
    'required' => false,
  ));
  $validators['sort_order'] = new sfValidatorChoice(array(
    'choices' => array('asc', 'desc'),
    'required' => false,
  ));
}