<?php
$embedded_relations_fields = $this->configuration->getValue('default.embedded_relations_fields');
$fields = $this->configuration->getValue('default.fields');
$specific_configuration_directives = false;

foreach ($fields as $field => $configuration)
{
  if (isset($configuration['date_format']) || isset($configuration['tag_name']) || isset($configuration['type']))
  {
    $specific_configuration_directives = true;
    continue;
  }
}
foreach ($embedded_relations_fields as $embed => $e_r_fields)
{
  foreach ($e_r_fields as $e_r_fields => $configuration)
  {
    if (isset($configuration['date_format']) || isset($configuration['tag_name']) || isset($configuration['type']))
    {
      $specific_configuration_directives = true;
      break 2;
    }
  }
}
?>
<?php foreach ($embedded_relations_fields as $embed => $e_r_fields): ?>
  /**
   * Allows to change configuration of the fields of the embedded object
   * '<?php echo $embed; ?>'
   *
   * @param array $object  An associative array representing the object
   * @return array  The transformed object
   */
  protected function configureFieldsEmbedded<?php echo $embed;?>(array $object)
  {
<?php foreach ($e_r_fields as $field => $configuration): ?>
<?php if (isset($configuration['date_format']) || isset($configuration['tag_name']) || isset($configuration['type'])): ?>
    if (isset($object['<?php echo $field; ?>']))
    {
<?php if (isset($configuration['date_format'])): ?>
      $object['<?php echo $field ?>'] = date('<?php echo $configuration['date_format'] ?>', strtotime($object['<?php echo $field ?>']));
<?php endif; ?>
<?php if (isset($configuration['type'])): ?>
      $object['<?php echo $field ?>'] = is_null($object['<?php echo $field ?>']) ? null : (<?php echo $configuration['type'] ?>) $object['<?php echo $field ?>'];
<?php endif; ?>
<?php if (isset($configuration['tag_name'])): ?>
      $object['<?php echo $configuration['tag_name'] ?>'] = $object['<?php echo $field ?>'];
      unset($object['<?php echo $field ?>']);
<?php endif; ?>
    }
<?php endif; ?>
<?php endforeach; ?>
    return $object;
  }

<?php endforeach; ?>

  /**
   * Allows to change configure some fields of the response, based on the
   * generator.yml configuration file. Supported configuration directives are
   * "date_format" and "tag_name"
   *
   * @return  void
   */
  protected function configureFields()
  {
<?php if ($specific_configuration_directives): ?>
    foreach ($this->objects as $i => $object)
    {
<?php foreach ($embedded_relations_fields as $embed => $e_r_fields): ?>
      if (isset($object['<?php echo $embed; ?>']))
      {
        if (isset($object['<?php echo $embed; ?>'][0])) // guess that this is an array of <?php echo $embed; ?> relations
        {
          foreach ($object['<?php echo $embed; ?>'] as &$embedded_object) // reference for in-place editing
          {
            $embedded_object = $this->configureFieldsEmbedded<?php echo $embed; ?>($embedded_object);
          }
        }
        else
        {
          $object['<?php echo $embed; ?>'] = $this->configureFieldsEmbedded<?php echo $embed; ?>($object['<?php echo $embed; ?>']);
        }
      }
<?php endforeach; ?>

<?php foreach ($fields as $field => $configuration): ?>
<?php if (isset($configuration['date_format']) || isset($configuration['tag_name']) || isset($configuration['type'])): ?>
      if (isset($object['<?php echo $field ?>']))
      {
<?php if (isset($configuration['date_format'])): ?>
        $object['<?php echo $field ?>'] = date('<?php echo $configuration['date_format'] ?>', strtotime($object['<?php echo $field ?>']));
<?php endif; ?>
<?php if (isset($configuration['type'])): ?>
	     $object['<?php echo $field ?>'] = is_null($object['<?php echo $field ?>']) ? null : (<?php echo $configuration['type'] ?>) $object['<?php echo $field ?>'];
<?php endif; ?>
<?php if (isset($configuration['tag_name'])): ?>
      }
      if (array_key_exists('<?php echo $field ?>', $object))
      {
        $object['<?php echo $configuration['tag_name'] ?>'] = $object['<?php echo $field ?>'];
        unset($object['<?php echo $field ?>']);
<?php endif; ?>
      }
<?php endif; ?>
<?php endforeach; ?>

      $this->objects[$i] = $object;
    }
<?php endif; ?>
  }
