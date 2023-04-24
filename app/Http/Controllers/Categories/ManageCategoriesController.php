<?php

namespace App\Http\Controllers\Categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Categories\CategoryInterface;
use Illuminate\Support\Facades\Validator;

class ManageCategoriesController extends Controller {
	protected $manageCategory;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CategoryInterface $interface) {
	    $this->manageCategory = $interface;
	}
	
	/**
	 * Show the application author manage page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		try {
			return view ( 'dashboard.categories.manage' );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	public function getCategory($encodedURI = null, Request $request) {
		try {
			$id = explode ( '=', $encodedURI ) [1];
			return $this->manageCategory->getCategory ( [ 
					'category_id_pk' => $id 
			] );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	public function getCategories(Request $request) {
		try {
		    return $this->manageCategory->getCategories ( [ ] );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	/**
	 * Get a validator for an incoming login request.
	 *
	 * @param array $data        	
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data) {
		return Validator::make ( $data, [ 
				'name' => 'required',
				'status' => 'required',
		        'category_level' => 'required',
		        'parent_category_id' => 'required_unless:category_level,==,1',
				'color' => 'required' 
		], [ 
				'required' => 'The :attribute is required.' 
		] );
	}
	public function statusCategoryUpdate(Request $request) {
		try {
		    $status = $this->manageCategory->statusCategoryUpdate ( $request->all () );
			if ($status)
				return json_encode ( array (
						'status' => $status 
				) );
		} catch ( \Exception $e ) {
			abort ( 500 );
		}
	}
	public function saveCategory(Request $request) {
		/**
		 * Validate
		 */
		$this->validator ( $request->all () )->validate ();
		return $this->manageCategory->saveOrEditCategory ( $request->all () );
	}
	public function deleteCategory($encodedURI = null, Request $request) {
		try {
			$id = explode ( '=', $encodedURI ) [1];
			$status = $this->manageCategory->categoryDelete ( $id );
			if ($status)
				return json_encode ( array (
						'status' => $status 
				) );
		} catch ( \Exception $e ) {
			abort ( 500 );
		}
	}
}
