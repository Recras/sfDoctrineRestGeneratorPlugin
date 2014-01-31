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

    $request->setRequestFormat('html');

    try
    {
      $this->validateCreate($content);
    }
    catch (Exception $e)
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

    $this->object = $this->createObject();
    $this->updateObjectFromRequest($content);
    $this->getResponse()->setStatusCode(201);
    $this->doSave();
    $this->getResponse()->setHttpHeader('Location', $this->getUrlForAction('show', false));
    return sfView::NONE;
  }

  protected function createObject()
  {
    return new $this->model();
  }

  protected function doSave()
  {
    $this->object->save();

    return sfView::NONE;
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
          $this->validate($value, $validators[$name], $prefix.$name.'.');
        }
        else
        {
          $validators[$name]->clean($value);
        }
      }
    }
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

  protected function updateObjectFromRequest($content)
  {
    $this->object->importFrom('array', $this->parsePayload($content));
  }

  /**
   * Applies a set of validators to an array of parameters
   *
   * @param array   $params      An array of parameters
   * @param array   $validators  An array of validators
   * @throw sfException
   */
  public function validate($params, $validators, $prefix = '')
  {
    $unused = array_keys($validators);

    foreach ($params as $name => $value)
    {
      if (!isset($validators[$name]))
      {
        throw new sfException(sprintf('Could not validate extra field "%s"', $prefix.$name));
      }
      else
      {
        if (is_array($validators[$name]))
        {
          // validator for a related object
          $this->validate($value, $validators[$name], $prefix.$name.'.');
        }
        else
        {
          $validators[$name]->clean($value);
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
          $validators[$name]->clean(null);
        }
      }
      catch (Exception $e)
      {
        throw new sfException(sprintf('Could not validate field "%s": %s', $prefix.$name, $e->getMessage()));
      }
    }
  }

  /**
   * Applies the creation validators to the payload posted to the service
   *
   * @param   string   $payload  A payload string
   */
  public function validateCreate($payload)
  {
    $params = $this->parsePayload($payload);

    $validators = $this->getCreateValidators();
    $this->validate($params, $validators);

    $postvalidators = $this->getCreatePostValidators();
    $this->postValidate($params, $postvalidators);
  }

  /**
   * Applies the get validators to the constraint parameters passed to the
   * webservice
   *
   * @param   array   $params  An array of criterions used for the selection
   */
  public function validateIndex($params)
  {
    $validators = $this->getIndexValidators();
    $this->validate($params, $validators);

    $postvalidators = $this->getIndexPostValidators();
    $this->postValidate($params, $postvalidators);
  }

  /**
   * Applies the get validators to the constraint parameters passed to the
   * webservice
   *
   * @param   array   $params  An array of criterions used for the selection
   */
  public function validateShow($params)
  {
  	$validators = $this->getIndexValidators();
  	$this->validate($params, $validators);

  	$postvalidators = $this->getIndexPostValidators();
  	$this->postValidate($params, $postvalidators);
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
    $this->validate($params, $validators);

    $postvalidators = $this->getUpdatePostValidators();
    $this->postValidate($params, $postvalidators);
  }
}
