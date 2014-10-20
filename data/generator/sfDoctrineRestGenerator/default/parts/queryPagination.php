  /**
   * Add pagination to a specified query object
   *
   * @param Doctrine_Query $query  The query to add pagination to
   * @param array &$params  The parameters
   * @return Doctrine_Query  The query, amended with pagination
   */
  protected function queryPagination(Doctrine_Query $query, array &$params)
  {
<?php
$max_items = $this->configuration->getValue('get.max_items');
$pagination_custom_page_size = $this->configuration->getValue('get.pagination_custom_page_size');
$pagination_enabled = $this->configuration->getValue('get.pagination_enabled');
$pagination_page_size = $this->configuration->getValue('get.pagination_page_size');
?>
<?php if ($pagination_enabled): ?>
    $page_size = <?php echo $pagination_page_size; ?>;
<?php if ($pagination_custom_page_size): ?>

    if (isset($params['page_size']))
    {
      $page_size = $params['page_size'];
      unset($params['page_size']);
    }

<?php endif; ?>
<?php if ($max_items > 0): ?>
    $page_size = $page_size === 0 ? <?php echo $max_items; ?> : $page_size;
<?php endif; ?>

    if (!isset($params['page']))
    {
      $params['page'] = 1;
    }
    $query->offset(($params['page'] - 1) * $page_size);
    unset($params['page']);

    $query->limit($page_size);
<?php elseif ($max_items > 0): // !$pagination_enabled ?>
    $query->limit(<?php echo $max_items; ?>);
<?php endif; ?>

    return $query;
  }
