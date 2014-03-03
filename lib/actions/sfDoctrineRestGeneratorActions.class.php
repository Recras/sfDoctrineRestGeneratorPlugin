<?php

class sfDoctrineRestGeneratorActions extends sfActions
{
  /**
   * Creates a <?php echo $this->getModelClass() ?> object
   * @param   sfWebRequest   $request a request object
   * @return  string
   */
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $content = $this->getContent();

    $request->setRequestFormat('html');

    try
    {
      $params = $this->validateCreate($content);
    }
    catch (Exception $e)
    {
      return $this->handleException($e);
    }

    $this->object = $this->createObject();
    $this->updateObjectFromParameters($params);
    $this->getResponse()->setStatusCode(201);
    $this->doSave();
    $this->getResponse()->setHttpHeader('Location', $this->getUrlForAction('show', false));
    return sfView::SUCCESS;
  }

  protected function createObject()
  {
    return new $this->model();
  }

  protected function doSave()
  {
    $this->object->save();

    $this->objects = array($this->object->toArray(false));
    $this->formatObjects(array());
    $this->outputObjects(false);
    $this->setTemplate('index');

    return sfView::SUCCESS;
  }

  protected function getContent()
  {
    $request = $this->getRequest();
    $content = $request->getContent();

    // Restores backward compatibility. Content can be the HTTP request full body, or a form encoded "content" var.
    if (strpos($content, 'content=') === 0)
    {
      $content = $request->getParameter('content');
    }
    if ($content === false)
    {
      $content = $request->getPostParameter('content'); // Last chance to get the content!
    }

    return $content;
  }

  protected function getPaginationValidators()
  {
    return array();
  }

  protected function getSortValidators()
  {
    return array();
  }

  /**
   * Returns the list of validators for an update request.
   * @return  array  an array of validators
   */
  public function getUpdatePostValidators()
  {
    return $this->getCreatePostValidators() ;
  }

  /**
   * Returns the list of validators for an update request.
   * @return  array  an array of validators
   */
  public function getUpdateValidators()
  {
    return $this->getCreateValidators();
  }

  /**
   * Handle an exception
   * @param  Exception  exception
   * @return sfView::SUCCESS;
   */
  public function handleException(Exception $e)
  {
    $this->getResponse()->setStatusCode(406);
    $serializer = $this->getSerializer();
    $this->getResponse()->setContentType($serializer->getContentType());
    $error = $e->getMessage();

    // event filter to enable customisation of the error message.
    $result = $this->dispatcher->filter(
      new sfEvent($this, 'sfDoctrineRestGenerator.filter_error_output'),
      $error
    )->getReturnValue();

    if ($error === $result)
    {
      $error = array(array('message' => $error));
      $this->output = $serializer->serialize($error, 'error');
    }
    else
    {
      $this->output = $serializer->serialize($result);
    }

    $this->setTemplate('index');
    return sfView::SUCCESS;
  }

  /**
   * Output the objects
   *
   * @return void
   */
  protected function outputObjects($multiple = true)
  {
    $serializer = $this->getSerializer();
    $this->getResponse()->setContentType($serializer->getContentType());
    if ($multiple) {
      $this->output = $serializer->serialize($this->objects, $this->model);
    } else {
      $this->output = $serializer->serialize($this->objects[0], $this->model, false);
    }
  }

  /**
   * Applies a set of validators to an array of parameters
   *
   * @param array   $params      An array of parameters
   * @param array   $validators  An array of validators
   * @throw sfException
   */
  public function postValidate($params, $validators, $prefix = '')
  {
    foreach ($params as $name => $value)
    {
      if (isset($validators[$name]))
      {
        if (is_array($validators[$name]))
        {
          // validator for a related object
          $params[$name] = $this->validate($value, $validators[$name], $prefix.$name.'.');
        }
        else
        {
          $params[$name] = $validators[$name]->clean($value);
        }
      }
    }
    return $params;
  }

  /**
   * Execute the query for selecting a collection of objects, eventually
   * along with related objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function queryExecute($params)
  {
    $this->objects = $this->dispatcher->filter(
      new sfEvent(
        $this,
        'sfDoctrineRestGenerator.filter_results',
        array()
      ),
      $this->query($params)->execute(array(), Doctrine_Core::HYDRATE_ARRAY)
    )->getReturnValue();
  }

  /**
   * Execute the query for selecting an object, eventually along with related
   * objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function queryFetchOne($params)
  {
    $this->objects = array($this->dispatcher->filter(
      new sfEvent(
        $this,
        'sfDoctrineRestGenerator.filter_result',
        array()
      ),
      $this->query($params)->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY)
    )->getReturnValue());
  }

  protected function updateObjectFromParameters(array $parameters)
  {
    $this->object->fromArray($parameters);
  }

  protected function updateObjectFromRequest($content)
  {
    self::updateObjectFromParameters($this->parsePayload($content));
  }

  /**
   * Applies a set of validators to an array of parameters
   *
   * @param array   $params      An array of parameters
   * @param array   $validators  An array of validators
   * @return array  The cleaned parameters
   * @throw sfException
   */
  public function validate($params, $validators, $prefix = '')
  {
    $unused = array_keys($validators);

    foreach ($params as $name => $value)
    {
      if (!isset($validators[$name]))
      {
        throw new sfValidatorError(new sfValidatorPass(), sprintf('Could not validate extra field "%s"', $prefix.$name));
      }
      else
      {
        if (is_array($validators[$name]))
        {
          // validator for a related object
          $params[$name] = $this->validate($value, $validators[$name], $prefix.$name.'.');
        }
        else
        {
          $params[$name] = $validators[$name]->clean($value);
        }

        unset($unused[array_search($name, $unused, true)]);
      }
    }

    // are non given values required?
    foreach ($unused as $name)
    {
      try
      {
        if (!is_array($validators[$name]))
        {
          $params[$name] = $validators[$name]->clean(null);
        }
      }
      catch (sfValidatorError $e)
      {
        throw new sfValidatorError($e->getValidator(), sprintf('Could not validate field "%s": %s', $prefix.$name, $e->getMessage()));
      }
    }
    return $params;
  }

  /**
   * Applies the creation validators to the payload posted to the service
   *
   * @param   string   $payload  A payload string
   * @return  array    The cleaned parameters
   */
  public function validateCreate($payload)
  {
    $params = $this->parsePayload($payload);

    $validators = $this->getCreateValidators();
    $params = $this->validate($params, $validators);

    $postvalidators = $this->getCreatePostValidators();
    $params = $this->postValidate($params, $postvalidators);

    return $params;
  }

  /**
   * Applies the get validators to the constraint parameters passed to the
   * webservice
   *
   * @param   array   $params  An array of criterions used for the selection
   * @return  array   The cleaned parameters
   */
  public function validateIndex($params)
  {
    $validators = $this->getIndexValidators();
    $params = $this->validate($params, $validators);

    $postvalidators = $this->getIndexPostValidators();
    $params = $this->postValidate($params, $postvalidators);

    return $params;
  }

  /**
   * Applies the get validators to the constraint parameters passed to the
   * webservice
   *
   * @param   array   $params  An array of criterions used for the selection
   * @return  array   The cleaned parameters
   */
  public function validateShow($params)
  {
    $validators = $this->getIndexValidators();
    $params = $this->validate($params, $validators);

    $postvalidators = $this->getIndexPostValidators();
    $params = $this->postValidate($params, $postvalidators);

    return $params;
  }

  /**
   * Applies the update validators to the payload posted to the service
   *
   * @param   string   $payload  A payload string
   */
  public function validateUpdate($payload)
  {
    $params = $this->parsePayload($payload);

    $validators = $this->getUpdateValidators();
    $params = $this->validate($params, $validators);

    $postvalidators = $this->getUpdatePostValidators();
    $params = $this->postValidate($params, $postvalidators);

    return $params;
  }
}
