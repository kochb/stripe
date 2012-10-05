<?php
/**
 * Stripe subscription model
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2012, Brad Koch
 * @package stripe
 * @subpackage stripe.models
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('StripeAppModel', 'Stripe.Model');

/**
 * StripeSubscription
 *
 * @package stripe.models
 */
class StripeSubscription extends StripeAppModel {

/**
 * API path
 *
 * @var string
 */
	public $path = '/customers/%s/subscription';

    public $primaryKey = 'customer';

/**
 * Subscription schema
 *
 * @var array
 */
	public $_schema = array(
		'customer' => array('type' => 'string'),
        'status' => array('type' => 'string'),
		'current_period_end' => array('type' => 'integer'),
		'ended_at' => array('type' => 'integer'),
		'cancel_at_period_end' => array('type' => 'boolean'),
		'start' => array('type' => 'integer'),
		'canceled_at' => array('type' => 'integer'),
		'trial_start' => array('type' => 'integer'),
		'quantity' => array('type' => 'integer'),
		'trial_end' => array('type' => 'integer'),
		'current_period_start' => array('type' => 'integer'),
        'plan' => array(),
        // Card info
		'number' => array('type' => 'string'),
		'exp_month' => array('type' => 'string', 'length' => '2'),
		'exp_year' => array('type' => 'string', 'length' => '4'),
		'cvc' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'address_line_1' => array('type' => 'string'),
		'address_line_2' => array('type' => 'string'),
		'address_zip' => array('type' => 'string'),
		'address_state' => array('type' => 'string'),
		'address_country' => array('type' => 'string')
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
        'customer' => array(
 			'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please specify the customer you are subscribing.',
				'required' => true,
				'on' =>'create'
			)
        ),
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
