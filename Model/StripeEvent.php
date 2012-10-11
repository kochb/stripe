<?php
/**
 * Stripe event
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @subpackage stripe.models
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('StripeAppModel', 'Stripe.Model');

/**
 * StripeEvent
 *
 * @package stripe.models
 */
class StripeEvent extends StripeAppModel {

/**
 * API path
 *
 * @var string
 */
	public $path = '/events';

/**
 * Event schema
 *
 * @var array
 */
	public $_schema = array(
		'id' => array('type' => 'string'),
		'number' => array('type' => 'string'),
		'livemode' => array('type' => 'boolean'),
		'created' => array('type' => 'string'),
		'data' => array(),
		'pending_webhooks' => array('type' => 'integer'),
		'type' => array('type' => 'string'),
	);

}