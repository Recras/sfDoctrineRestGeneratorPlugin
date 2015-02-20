[?php

/**
 * <?php echo $this->getModuleName() ?> actions. REST API for the model "<?php echo $this->getModelClass() ?>"
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 *
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z xavier $
 */
class <?php echo $this->getGeneratedModuleName() ?>Actions extends <?php echo $this->getActionsBaseClass() ?>

{
  public $model = '<?php echo $this->getModelClass() ?>';
  protected $additional_params = <?php var_export($this->configuration->getValue('get.additional_params', array())); ?>;

<?php include dirname(__FILE__).'/../../parts/cleanupParameters.php' ?>

<?php include dirname(__FILE__).'/../../parts/configureFields.php' ?>

<?php include dirname(__FILE__).'/../../parts/deleteAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/formatObjects.php' ?>

<?php include dirname(__FILE__).'/../../parts/getCreateValidators.php' ?>

<?php include dirname(__FILE__).'/../../parts/getFormat.php' ?>

<?php include dirname(__FILE__).'/../../parts/getIndexValidators.php' ?>

<?php
if ($this->configuration->getValue('get.pagination_enabled'))
{
  include dirname(__FILE__).'/../../parts/getPaginationValidators.php';
}
?>

<?php include dirname(__FILE__).'/../../parts/getSerializer.php' ?>

<?php
if ($this->configuration->getValue('get.sort_custom'))
{
  include dirname(__FILE__).'/../../parts/getSortValidators.php';
}
?>

<?php include dirname(__FILE__).'/../../parts/getUrlForAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/indexAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/parsePayload.php' ?>

<?php include dirname(__FILE__).'/../../parts/queryAdditionnal.php' ?>

<?php include dirname(__FILE__).'/../../parts/queryEmbedRelations.php' ?>

<?php include dirname(__FILE__).'/../../parts/queryEmbedRelationsCustom.php' ?>

<?php include dirname(__FILE__).'/../../parts/queryFilterPrimaryKeys.php' ?>

<?php include dirname(__FILE__).'/../../parts/queryFilters.php' ?>

<?php include dirname(__FILE__).'/../../parts/queryPagination.php' ?>

<?php include dirname(__FILE__).'/../../parts/querySelect.php' ?>

<?php include dirname(__FILE__).'/../../parts/querySort.php' ?>

<?php include dirname(__FILE__).'/../../parts/setFieldVisibility.php' ?>

<?php include dirname(__FILE__).'/../../parts/updateAction.php' ?>
}
