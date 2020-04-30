<?php assert($this instanceof sfDoctrineRestGenerator); ?>
  public function getDisableCreateValidators()
  {
    return <?php echo $this->asPhp(isset($this->config['create']['disable_validators']) ? $this->config['create']['disable_validators'] : array()) ?>;
<?php unset($this->config['create']['disable_validators']) ?>
  }
