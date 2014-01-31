  /**
   * Retrieves a <?php echo $this->getModelClass() ?> object
   * @param   sfWebRequest   $request a request object
   * @return  string
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::GET));
    $params = $request->getParameterHolder()->getAll();

    // notify an event before the action's body starts
    $this->dispatcher->notify(new sfEvent($this, 'sfDoctrineRestGenerator.get.pre', array('params' => $params)));

    $request->setRequestFormat('html');
    $this->setTemplate('index');
    $params = $this->cleanupParameters($params);

    try
    {
      $format = $this->getFormat();
      $this->validateShow($params);
    }
    catch (Exception $e)
    {
      $this->getResponse()->setStatusCode(406);
      return $this->handleException($e);
    }

    $this->queryFetchOne($params);
    $this->forward404Unless(is_array($this->objects[0]));

    $this->formatObjects($params);

    $this->outputObjects(false);
    unset($this->objects);
  }
