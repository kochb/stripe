<?php
/**
 * Stripe datasource
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @subpackage stripe.models.datasources
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Imports
 */
App::uses('HttpSocket', 'Network/Http');
App::uses('CakeLog', 'Log');

/**
 * StripSource
 *
 * @package stripe
 * @subpackage stripe.models.datasources
 */
class StripeSource extends DataSource {

/**
 * HttpSocket
 *
 * @var HttpSocket
 */
	public $Http = null;

/**
 * Start quote
 * 
 * @var string 
 */
	public $startQuote = '';

/**
 * End quote
 * 
 * @var string 
 */
	public $endQuote = '';

/**
 * Constructor. Sets API key and throws an error if it's not defined in the
 * db config
 *
 * @param array $config
 */
	public function __construct($config = array()) {
		parent::__construct($config);

		if (empty($config['api_key'])) {
			throw new CakeException('StripeSource: Missing api key');
		}
		
		$this->Http = new HttpSocket();
	}

/**
 * Creates a record in Stripe
 *
 * @param Model $model The calling model
 * @param array $fields Array of fields
 * @param array $values Array of field values
 * @return boolean Success
 */
	public function create(Model $model, $fields = null, $values = null) {
        if (empty($fields)) $fields = array();
        if (empty($values)) $values = array();

		$request = array(
			'uri' => array(
				'path' => $this->getPath($model, null)
			),
			'method' => 'POST',
			'body' => $this->reformat($model, array_combine($fields, $values))
		);
		$response = $this->request($request);
		if ($response === false) {
			return false;
		}
		$model->setInsertId($response[$model->primaryKey]);
		$model->id = $response[$model->primaryKey];
		return true;
	}

/**
 * Reads a Stripe record
 *
 * @param Model $model The calling model
 * @param array $queryData Query data (conditions, limit, etc)
 * @return mixed `false` on failure, data on success
 */
	public function read(Model $model, $queryData = array(), $recursive = null) {
		// If calculate() wants to know if the record exists. Say yes.
		if ($queryData['fields'] == 'COUNT') {
			return array(array(array('count' => 1)));
		}

		if (empty($queryData['conditions'][$model->alias.'.'.$model->primaryKey]) && ! empty($model->id)) {
			$queryData['conditions'][$model->alias.'.'.$model->primaryKey] = $model->id;
		}
        $multiple = empty($queryData['conditions'][$model->alias.'.'.$model->primaryKey]);

		$request = array(
			'uri' => array(
				'path' => $this->getPath($model, $multiple ? null : $queryData['conditions'][$model->alias.'.'.$model->primaryKey])
			)
		);
        unset($queryData['conditions'][$model->alias.'.'.$model->primaryKey]);
        if (!empty($queryData['conditions'])) {
            $request['uri']['query'] = $queryData['conditions'];
        }
		$response = $this->request($request);

		if ($response === false) {
			return false;
		}
        
        if (! $multiple) {
            $response = array('data' => array($response));
        }

        $result = array();
        foreach ($response['data'] as $record) {
            $result[] = array($model->alias => $record);
        }

		return $result;
	}

/**
 * Updates a Stripe record
 *
 * @param Model $model The calling model
 * @param array $fields Array of fields to update
 * @param array $values Array of field values
 * @return mixed `false` on failure, data on success
 */
	public function update(Model $model, $fields = null, $values = null, $conditions = null) {
        if (empty($fields)) $fields = array();
        if (empty($values)) $values = array();

		$data = array_combine($fields, $values);
		if (!isset($data[$model->primaryKey])) {
			$data[$model->primaryKey] = $model->id;
		}
		$id = $data[$model->primaryKey];
		unset($data[$model->primaryKey]);
		$request = array(
			'uri' => array(
				'path' => $this->getPath($model, $id)
			),
			'method' => 'POST',
			'body' => $this->reformat($model, $data)
		);

		$response = $this->request($request);
		if ($response === false) {
			return false;
		}
		$model->id = $id;
		return array($model->alias => $response);
	}

/**
 * Deletes a Stripe record
 *
 * @param Model $model The calling model
 * @param integer $id Id to delete
 * @return boolean Success
 */
	public function delete(Model $model, $id = null) {
		$request = array(
			'uri' => array(
				'path' => $this->getPath($model, $id[$model->alias.'.'.$model->primaryKey])
			),
			'method' => 'DELETE'
		);

        if (! empty($model->_delete_args)) {
            $request['body'] = $model->_delete_args;
        }

		$response = $this->request($request);
		if ($response === false) {
			return false;
		}
		return true;
	}

/**
 * Submits a request to Stripe. Requests are merged with default values, such as
 * the api host. If an error occurs, it is stored in `$lastError` and `false` is
 * returned.
 *
 * @param array $request Request details
 * @return mixed `false` on failure, data on success
 */
	public function request($request = array()) {
		$this->lastError = null;
		$this->request = array(
			'uri' => array(
				'host' => 'api.stripe.com',
				'scheme' => 'https',
				'path' => '/',
			),
			'method' => 'GET',
		);
		$this->request = Set::merge($this->request, $request);
		$this->request['uri']['path'] = '/v1/' . trim($this->request['uri']['path'], '/');
		$this->Http->configAuth('Basic', $this->config['api_key'], '');

		try {
			$response = $this->Http->request($this->request);
			switch ($this->Http->response['status']['code']) {
				case '200':
					return json_decode($response, true);
				break;
				case '402':
					$error = json_decode($response, true);
					$this->lastError = $error['error']['message'];
					return false;
				break;
				default:
					$this->lastError = 'Unexpected error.';
					CakeLog::write('stripe', $this->lastError);
					return false;
				break;
			}
		} catch (CakeException $e) {
			$this->lastError = $e->message;
			CakeLog::write('stripe', $e->message);
		}
	}
    
/**
 * Resolves the path attribute of the model, since some paths require an id
 * 
 * @param Model $model The calling model
 * @return string The appropriate path
 */
    public function getPath($model, $id) {
        $path = $model->path;

        if (strpos($model->path, '%s') === false) {
            $path = rtrim($path, '/') . '/%s';
        }

        return sprintf($path, $id);
    }

/**
 * Formats data for Stripe based on `$formatFields`
 *
 * @param Model $model The calling model
 * @param array $data Data sent by Cake
 * @return array Stripe-formatted data
 */
	public function reformat($model, $data) {
		if (!isset($model->formatFields)) {
			return $data;
		}
		foreach ($data as $field => $value) {
			foreach ($model->formatFields as $key => $fields) {
				if (in_array($field, $fields)) {
					$data[$key][$field] = $value;
					unset($data[$field]);
				}
			}
		}
		return $data;
	}

/**
 * For checking if record exists. Return COUNT to have read() say yes.
 *
 * @param Model $Model
 * @param string $func
 * @return true
 */
	public function calculate(Model $Model, $func) {
		return 'COUNT';
	}

/**
 * Don't use internal caching
 *
 * @return null
 */
	public function listSources($data = null) {
		return null;
	}

/**
 * Descibe with schema. Check the model or use nothing.
 *
 * @param Model $Model
 * @return array
 */
	public function describe($model) {
		if (isset($Model->_schema)) {
			return $Model->_schema;
		} else {
			return null;
		}
	}

}