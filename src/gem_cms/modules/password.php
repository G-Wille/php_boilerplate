<?php

/**
* Password Util
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

define ("SALTY","234IOnr21");

class password {
  const SALTY = "234IOnr21";

  public static function generate() {
    $length = 5;
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
	}

	public static function encodePassword($password) {
		return md5(SALTY.$password);
	}

	public static function checkPassword($password,$hash) {
		if (md5(SALTY.$password)===$hash) return true;
		else return false;
	}

	public static function isValid($password) {
		if (strlen($password) < 5) return false;
		return true;
	}
}
?>
