<?php

namespace App\Helpers;

use URL;

class UrlGenerator {
	public static function getUrlFromSlug($url = 'home', $data) {
		switch ($url) {
			case 'home' :
				return URL::to ( '/' ) . '?' . http_build_query ( $data );
				break;
			default :
				return URL::to ( '/' ) . '?' . http_build_query ( $data );
				break;
		}
	}
}