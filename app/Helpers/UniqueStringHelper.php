<?php

namespace App\Helpers;
use Uuid;

class UniqueStringHelper {
	public static function random() {
		return (string) Uuid::generate(4);
	}
	public static function md5Random($string) {
		return (string) Uuid::generate(3,$string, Uuid::NS_DNS);
	}
	public static function sha1Random($string) {
		return (string) Uuid::generate(5,$string, Uuid::NS_DNS);
	}
	public static function timeBasedRandom(){
		return (string) Uuid::generate(1);
	}
}