<?php
abstract class annex {
	/**
	 * @author Stephen Watkins
	 * @url http://stackoverflow.com/questions/4356289/php-random-string-generator
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	public static function gen_rnd_str($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
} 