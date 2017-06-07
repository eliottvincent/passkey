<?php

/**
 * Created by PhpStorm.
 * User: eliottvincent
 * Date: 07/06/2017
 * Time: 09:53
 */
class TrackingVO
{
	private static $trackingTypes = array(
		"borrowing_creation",
		"borrowing_update",
		"borrowing_deletion",

		"door_creation",
		"door_update",
		"door_deletion",

		"keychain_creation",
		"keychain_update",
		"keychain_deletion",

		"key_creation",
		"key_update",
		"key_deletion",

		"lock_creation",
		"lock_update",
		"lock_deletion",

		"user_creation",
		"user_update",
		"user_deletion",
	);

	private $id; // 32 bits

	private $type;

	private $date;

}
