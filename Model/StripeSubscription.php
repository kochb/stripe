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
		'current_period_start' => array('type' => 'integer')
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
}
