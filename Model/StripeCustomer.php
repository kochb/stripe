<?php
/**
 * Stripe credit card model
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
 * StripeCustomer
 *
 * @package stripe.models
 */
class StripeCustomer extends StripeAppModel {

/**
 * API path
 *
 * @var string
 */
	public $path = '/customers';

/**
 * Credit Card schema
 *
 * @var array
 */
	public $_schema = array(
		'id' => array('type' => 'integer', 'length' => '12'),
		'number' => array('type' => 'string'),
		'exp_month' => array('type' => 'string', 'length' => '2'),
		'exp_year' => array('type' => 'string', 'length' => '4'),
		'cvc' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'address_line_1' => array('type' => 'string'),
		'address_line_2' => array('type' => 'string'),
		'address_zip' => array('type' => 'string'),
		'address_state' => array('type' => 'string'),
		'address_country' => array('type' => 'string'),
		'email' => array('type' => 'string'),
		'description' => array('type' => 'string'),
		'plan' => array('type' => 'string'),
		'trial_end' => array('type' => 'string'),
		'coupon' => array('type' => 'string')
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'number' => array(
			'credit_card' => array(
				'rule' => array('cc', array('visa', 'mc', 'amex', 'disc', 'jcb')),
                'allowEmpty' => false,
				'message' => 'Invalid credit card number.'
			)
		),
		'exp_month' => array(
			'between '=> array(
				'rule' => array('between', 1, 12),
                'allowEmpty' => false,
				'message' => 'Please enter a valid month.'
			)
		),
		'exp_year' => array(
			'between '=> array(
				'rule' => array('between', 4, 4),
                'allowEmpty' => false,
				'message' => 'Please enter a valid year.'
			)
		),
		'cvc' => array(
			'number' => array(
				'rule' => 'numeric',
                'allowEmpty' => false,
				'message' => 'Please enter a valid CVC.'
			)
		),
		'address_zip' => array(
			'rule' => array('postal', null, 'us'),
            'allowEmpty' => false,
			'message' => 'Please enter a valid zipcode.'
		)
	);

/**
 * hasOne associations
 *
 * @var array
 */
    public $hasOne = array(
		'StripeSubscription' => array(
			'className' => 'Stripe.StripeSubscription',
			'foreignKey' => 'customer',
			'dependent' => false,
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
		'StripeInvoice' => array(
			'className' => 'Stripe.StripeInvoice',
			'foreignKey' => 'customer',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
        ),
		'StripeInvoiceItem' => array(
			'className' => 'Stripe.StripeInvoiceItem',
			'foreignKey' => 'customer',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => ''
        )
    );

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'StripePlan' => array(
			'className' => 'Stripe.StripePlan',
			'foreignKey' => 'plan',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
    );

/**
 * Formats data for Stripe
 *
 * Fields within a key will be moved into that key when sent to Stripe. Everything
 * else will remain intact.
 *
 * @var array
 */
	public $formatFields = array(
		'card' => array(
			'number',
			'exp_month',
			'exp_year',
			'cvc',
			'name',
			'address_line_1',
			'address_1ine_2',
			'address_zip',
			'address_state',
			'address_country'
		)
	);

}