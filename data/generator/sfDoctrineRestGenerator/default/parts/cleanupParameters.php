  /**
   * Cleans up the request parameters
   *
   * @param   array  $params  an array of parameters
   * @return  array  an array of cleaned parameters
   */
  protected function cleanupParameters($params)
  {
    unset($params['sf_format']);
    unset($params['module']);
    unset($params['action']);

    foreach ($params as $name => $value)
    {
      if ((null === $value) || ('' === $value) || in_array($name, $this->additional_params))
      {
        unset($params[$name]);
      }
    }

    return $params;
  }
