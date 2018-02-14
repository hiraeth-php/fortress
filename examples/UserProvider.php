<?php

namespace Hiraeth\Fortress;

use People;
use Fortress;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


/**
 *
 */
class UserProvider implements Fortress\UserProvider
{
	/**
	 *
	 */
	public function __construct(People $people)
	{
		$this->people = $people;
	}


	/**
	 *
	 */
	public function getName()
	{
		return 'native';
	}


	/**
	 *
	 */
	public function initialize(Request $request, Response $response)
	{
		$params   = $request->getParsedBody();
		$login    = $params['login'];
		$password = $params['password'];

		if (!$person = $this->people->findOneByEmail($login)) {
			throw new Fortress\InvalidLoginException();
		}

		if (!password_verify($password, $person->getAccount()->getPassword())) {
			throw new Fortress\InvalidPasswordException();
		}

		$this->gateway->setToken($login);

		return $response;
	}


	/**
	 *
	 * @return array|NULL
	 */
	public function getData($token)
	{
		$person = $this->people->findOneByEmail($token);

		return $person && $person->getAccount()
			? $person->getId()
			: NULL;
	}


	/**
	 *
	 */
	public function setGateway(Fortress\Gateway $gateway)
	{
		$this->gateway = $gateway;
	}
}
