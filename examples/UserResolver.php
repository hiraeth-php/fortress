<?php

namespace Hiraeth\Fortress;

use Accounts;
use Fortress;

/**
 *
 */
class UserResolver implements Fortress\UserResolver
{
	/**
	 *
	 */
	public function __construct(Accounts $accounts)
	{
		$this->accounts = $accounts;
	}


	/**
	 *
	 */
	public function fetch($id)
	{
		if (!$id || !$account = $this->accounts->find($id)) {
			return new class {
				/**
				 *
				 */
				public function getPermissions()
				{
					return [];
				}


				/**
				 *
				 */
				public function getRoles()
				{
					return ['Anonymous'];
				}
			};
		}

		return $account;
	}
}
