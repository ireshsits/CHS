<?php

namespace App\Http\Controllers\Categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Categories\CategoryInterface;

class CategoryController extends Controller {
	protected $manageCategory;
	public function __construct(CategoryInterface $interface) {
	    $this->manageCategory = $interface;
	}
	public function getCategoriesForSearch($level = null, Request $request) {
	    if(!is_null($level))
	       $request->merge(['level' => $level]);
		return json_encode ( array (
				"result" => "success",
		        'items' => $this->manageCategory->getCategoriesForSearch( $request->all () ) 
		) );
	}
// 	public function getSubCategoriesForSearch(Request $request) {
// 		return json_encode ( array (
// 				"result" => "success",
// 		        'items' => $this->manageCategory->getSubCategoriesForSearch( $request->all () )
// 		) );
// 	}
}
