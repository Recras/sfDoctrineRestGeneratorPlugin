  /**
   * Updates a <?php echo $this->getModelClass() ?> object
   * @param   sfWebRequest   $request a request object
   * @return  string
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::PUT));
    $content = $this->getContent();

    $request->setRequestFormat('html');

    try
    {
      $params = $this->validateUpdate($content);
    }
    catch (sfValidatorError $e)
    {
      $this->getResponse()->setStatusCode(406);
      return $this->handleException($e);
    }

    // retrieve the object
<?php $primaryKey = Doctrine_Core::getTable($this->getModelClass())->getIdentifier() ?>
    $primaryKey = $request->getParameter('<?php echo $primaryKey ?>');
    $this->object = Doctrine_Core::getTable($this->model)->findOneBy<?php echo sfInflector::camelize($primaryKey) ?>($primaryKey);
    $this->forward404Unless($this->object);

    // update and save it
    $this->updateObjectFromParameters($params);

    return $this->doSave();
  }
