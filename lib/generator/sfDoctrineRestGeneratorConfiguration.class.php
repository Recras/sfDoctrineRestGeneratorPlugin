<?php

abstract class sfDoctrineRestGeneratorConfiguration
{
  /** @var array<string,array<string,mixed>> */
  protected $configuration = array();

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->compile();
  }

  abstract public function getFieldsDefault();
  abstract public function getEmbeddedRelationsFieldsDefault();
  abstract public function getFormatsEnabled();
  abstract public function getFormatsStrict();
  abstract public function getSeparator();
  abstract public function getAdditionalParams();
  abstract public function getDefaultFormat();
  abstract public function getDisplay();
  abstract public function getEmbedRelations();
  abstract public function getEmbedRelationsCustom();
  abstract public function getEmbedRelationsDisplay();
  abstract public function getEmbeddedRelationsHide();
  abstract public function getFieldsGet();
  abstract public function getFilters();
  abstract public function getGlobalAdditionalFields();
  abstract public function getHide();
  abstract public function getMaxItems();
  abstract public function getObjectAdditionalFields();
  abstract public function getPaginationCustomPageSize();
  abstract public function getPaginationEnabled();
  abstract public function getPaginationPageSize();
  abstract public function getSortCustom();
  abstract public function getSortDefault();
  abstract public function getDisableCreateValidators();

  protected function compile(): void
  {
    $this->configuration = array(
      'default' => array(
        'fields'                      => $this->getFieldsDefault(),
        'embedded_relations_fields'   => $this->getEmbeddedRelationsFieldsDefault(),
        'formats_enabled'             => $this->getFormatsEnabled(),
        'formats_strict'              => $this->getFormatsStrict(),
        'separator'                   => $this->getSeparator()
      ),
      'get'     => array(
        'additional_params'           => $this->getAdditionalParams(),
        'default_format'              => $this->getDefaultFormat(),
        'display'                     => $this->getDisplay(),
        'embed_relations'             => $this->getEmbedRelations(),
        'embed_relations_custom'      => $this->getEmbedRelationsCustom(),
        'embed_relations_display'     => $this->getEmbedRelationsDisplay(),
        'embedded_relations_hide'     => $this->getEmbeddedRelationsHide(),
        'fields'                      => $this->getFieldsGet(),
        'filters'                     => $this->getFilters(),
        'global_additional_fields'    => $this->getGlobalAdditionalFields(),
        'hide'                        => $this->getHide(),
        'max_items'                   => $this->getMaxItems(),
        'object_additional_fields'    => $this->getObjectAdditionalFields(),
        'pagination_custom_page_size' => $this->getPaginationCustomPageSize(),
        'pagination_enabled'          => $this->getPaginationEnabled(),
        'pagination_page_size'        => $this->getPaginationPageSize(),
        'sort_custom'                 => $this->getSortCustom(),
        'sort_default'                => $this->getSortDefault()
      ),
      'create'  => array(
        'disable_validators'          => $this->getDisableCreateValidators(),
      ),
    );
  }

  /**
   * Gets the value for a given key.
   *
   * @param array<string,mixed>  $config  The configuration
   * @param string $key     The key name
   * @param mixed  $default The default value
   *
   * @return mixed The key value
   */
  static public function getFieldConfigValue($config, string $key, $default = null)
  {
    $ref   =& $config;
    $parts =  explode('.', $key);
    $count =  count($parts);
    for ($i = 0; $i < $count; $i++)
    {
      $partKey = $parts[$i];
      if (!isset($ref[$partKey]))
      {
        return $default;
      }

      if ($count == $i + 1)
      {
        return $ref[$partKey];
      }
      else
      {
        $ref =& $ref[$partKey];
      }
    }

    return $default;
  }

  public function getContextConfiguration(string $context)
  {
    if (!isset($this->configuration[$context]))
    {
      throw new InvalidArgumentException(sprintf('The context "%s" does not exist.', $context));
    }

    return $this->configuration[$context];
  }

  /**
   * Gets the configuration for a given field.
   *
   * @param mixed   $default The default value if none has been defined
   *
   * @return mixed The configuration value
   */
  public function getValue(string $key, $default = null, bool $escaped = false)
  {
    if (preg_match('/^(?P<context>[^\.]+)\.(?P<key>.+)$/', $key, $matches))
    {
      $v = sfModelGeneratorConfiguration::getFieldConfigValue($this->getContextConfiguration($matches['context']), $matches['key'], $default);
    }
    elseif (!isset($this->configuration[$key]))
    {
      throw new InvalidArgumentException(sprintf('The key "%s" does not exist.', $key));
    }
    else
    {
      $v = $this->configuration[$key];
    }

    return $escaped ? str_replace("'", "\\'", $v) : $v;
  }
}
