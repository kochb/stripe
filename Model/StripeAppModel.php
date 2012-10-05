<?php
/**
 * Stripe app model
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');

/**
 * StripeAppModel
 *
 * @package stripe
*/
class StripeAppModel extends AppModel {

/**
 * The datasource
 *
 * @var string
 */
	public $useDbConfig = 'stripe';

/**
 * No table here
 *
 * @var mixed
 */
	public $useTable = false;

/**
 * Returns the last error from Stripe
 *
 * @return string Error
 */
	public function getStripeError() {
		$ds = ConnectionManager::getDataSource($this->useDbConfig);
		return $ds->lastError;
	}

/**
 * Overrides base delete to allow arguments.  Subscriptions need these.
 *
 * @param integer|string $id ID of record to delete
 * @param boolean $cascade Set to true to delete records that depend on this record
 * @param array $args Any arguments to the deletion
 * @return boolean True on success
 */
	public function delete($id = null, $cascade = true, $args = array()) {
        $this->_delete_args = $args;
        $result = parent::delete($id, $cascade);
        unset($this->_delete_args);

        return $result;
	}

/**
 * Overrides base deleteAll to allow arguments.  Subscriptions need these.
 *
 * @param mixed $conditions Conditions to match
 * @param boolean $cascade Set to true to delete records that depend on this record
 * @param boolean $callbacks Run callbacks
 * @param array $args Any arguments to the deletion
 * @return boolean True on success, false on failure
 */
	public function deleteAll($conditions, $cascade = true, $callbacks = false, $args = array()) {
        $this->_delete_args = $args;
        $result = parent::deleteAll($conditions, $cascade, $callbacks);
        unset($this->_delete_args);

        return $result;
    }

}
