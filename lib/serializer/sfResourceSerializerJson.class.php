<?php

class sfResourceSerializerJson extends sfResourceSerializer
{
  public function getContentType()
  {
    return 'application/json';
  }

  public function serialize($array, $rootNodeName = 'data', $collection = true)
  {
    return json_encode($array);
  }

  public function unserialize($payload)
  {
    $output = json_decode($payload, true);
    if (json_last_error() !== JSON_ERROR_NONE)
    {
      throw new sfDRGUnserializeException("JSON parsing error: ". json_last_error_msg());
    }
    return $output;
  }
}