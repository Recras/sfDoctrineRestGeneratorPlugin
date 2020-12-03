<?php assert($this instanceof sfDoctrineRestGenerator); ?>
<?php $global_additional_fields = $this->configuration->getValue('get.global_additional_fields', []); ?>
<?php $object_additional_fields = $this->configuration->getValue('get.global_additional_fields', []); ?>
<?php if ($global_additional_fields !== [] || $object_additional_fields !== []): ?>
  protected function parsePayload(?string $payload, bool $force = false): array
  {
    if ($force || !isset($this->_payload_array))
    {
      $payload_array = parent::parsePayload($payload, $force);

      $filter_params = <?php var_export(array_flip(array_merge($global_additional_fields, $object_additional_fields))) ?>;

      $this->_payload_array = array_diff_key($payload_array, $filter_params);
    }

    return $this->_payload_array;
  }

<?php endif; ?>
