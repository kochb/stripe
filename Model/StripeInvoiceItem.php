<?php
/**
 * Stripe invoice item
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
 * StripeInvoice
 *
 * @package stripe.models
 */
class StripeInvoiceItem extends StripeAppModel {

/**
 * API path
 *
 * @var string
 */
	public $path = '/invoiceitems';

/**
 * Invoice schema
 *
 * @var array
 */
	public $_schema = array(
		'id' => array('type' => 'string'),
		'object' => array('type' => 'string'),
		'livemode' => array('type' => 'boolean'),
        'amount' => array('type' => 'integer'),
        'currency' => array('type' => 'string'),
        'customer' => array('type' => 'string'),
        'date' => array('type' => 'integer'),
        'description' => array('type' => 'string'),
        'invoice' => array('type' => 'string')
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'StripeCustomer' => array(
			'className' => 'Stripe.StripeCustomer',
			'foreignKey' => 'customer',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'StripeInvoice' => array(
			'className' => 'Stripe.StripeInvoice',
			'foreignKey' => 'invoice',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
    );

}
