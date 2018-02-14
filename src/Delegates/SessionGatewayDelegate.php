<?php

namespace Hiraeth\Fortress;

use Hiraeth;
use Fortress\SessionGateway;

/**
 * Delegates are responsible for constructing dependencies for the dependency injector.
 */
class SessionGatewayDelegate implements Hiraeth\Delegate
{
	/**
	 *
	 */
	protected $app = NULL;


	/**
	 *
	 */
	protected $config = NULL;


	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass()
	{
		return 'Fortress\SessionGateway';
	}


	/**
	 * Get the interfaces for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return array A list of interfaces for which the delegate provides a class
	 */
	static public function getInterfaces()
	{
		return [];
	}


	/**
	 *
	 */
	public function __construct(Hiraeth\Application $app, Hiraeth\Configuration $config)
	{
		$this->app     = $app;
		$this->config  = $config;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Broker $broker The dependency injector instance
	 * @return Object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Broker $broker)
	{
		$gateway = new SessionGateway($broker->make('Fortress\UserResolver'));

		foreach ($this->config->get('fortress', 'fortress.providers', array()) as $provider) {
			$gateway->register($broker->make($provider));
		}

		return $gateway;
	}
}
