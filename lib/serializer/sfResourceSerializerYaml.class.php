<?php

class sfResourceSerializerYaml extends sfResourceSerializer
{
  public function getContentType()
  {
    return 'application/yaml';
  }

  public function serialize($array, $rootNodeName = 'data', $collection = true)
  {
    return sfYaml::dump(array($rootNodeName => $array), 5);
  }

  public function unserialize($payload)
  {
    try
    {
      $return = sfYaml::load($payload);
    }
    catch (InvalidArgumentException $e)
    {
      $err = new sfDRGUnserializeException("YAML parsing error: " . $e->getMessage());
      $err->setWrappedException($e);
      throw $err;
    }

    if (is_array($return))
    {
      $return = array_shift($return);
    }

    return $return;
  }
}