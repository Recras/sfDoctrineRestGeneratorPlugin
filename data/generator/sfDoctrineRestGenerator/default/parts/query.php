  /**
   * Create the query for selecting objects, eventually along with related
   * objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function query($params)
  {
    $q = Doctrine_Query::create()
      ->from($this->model.' '.$this->model);

    $this->querySelect($q, $params);

    $this->queryEmbedRelations($q, $params);
    $this->queryEmbedRelationsCustom($q, $params);

    $this->queryPagination($q, $params);
    $this->querySort($q, $params);

    $this->queryFilterPrimaryKeys($q, $params);
    $this->queryFilters($q, $params);

    foreach ($params as $name => $value)
    {
      $q->andWhere($this->model.'.'.$name.' = ?', $value);
    }

    return $q;
  }
