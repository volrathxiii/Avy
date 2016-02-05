<?php
class Response
{
	/**
	 *
	 * PROMPT|COMMAND|RESPONSE
	 * @var unknown_type
	 */
	private static $response_types = array('PROMPT','COMMAND','RESPONSE');

	public static $type;

	public static $response;

	public static function set($type, $response)
	{
		if(!in_array($type, self::$response_types)){
			echo "Invalid Response Type";
			return false;
		}
		self::$type = $type;
		self::$response = $response;

		return array(self::$type=>self::$response);
	}
}