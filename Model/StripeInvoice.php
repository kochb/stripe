<?php
/**
 * Stripe invoice
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
class StripeInvoice extends StripeAppModel {

/**
 * API path
 *
 * @var string
 */
	public $path = '/invoices';

/**
 * Invoice schema
 *
 * @var array
 */
	public $_schema = array(
		'id' => array('type' => 'string', 'length' => 17),
		'object' => array('type' => 'string', 'length' => 7),
		'livemode' => array('type' => 'boolean'),
        'closed' => array('type' => 'boolean'),
        'starting_balance' => array('type' => 'integer'),
        'period_end' => array('type' => 'integer'),
        'ending_balance' => array('type' => 'integer'),
        'lines' => array(),
        'total' => array('type' => 'integer'),
        'attempt_count' => array('type' => 'integer'),
        'charge' => array('type' => 'string', 'length' => 17),
        'subtotal' => array('type' => 'integer'),
        'attempted' => array('type' => 'boolean'),
        'next_payment_attempt' => array('type' => 'integer'),
        'date' => array('type' => 'integer'),
        'period_start' => array('type' => 'integer'),
        'paid' => array('type' => 'boolean'),
        'currency' => array('type' => 'string', 'length' => 3),
        'discount' => array(),
        'amount_due' => array('type' => 'integer'),
        'customer' => array('type' => 'string', 'length' => 18)
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
		)
    );

/**
 * hasMany associations
 *
 * @var array
 */
    public $hasMany = array(
		'StripeInvoiceItem' => array(
			'className' => 'Stripe.StripeInvoiceItem',
			'foreignKey' => 'invoice',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
        )
    );

}
