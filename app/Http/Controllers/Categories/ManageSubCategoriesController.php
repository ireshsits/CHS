<?php

namespace App\Http\Controllers\Categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Categories\CategoryInterface;
use Illuminate\Support\Facades\Validator;

class ManageSubCategoriesController extends Controller {
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
			return view ( 'dashboard.sub_categories.manage' );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	public function getSubCategory($encodedURI = null, Request $request) {
		try {
			$id = explode ( '=', $encodedURI ) [1];
			return $this->manageCategory->getSubCategory ( [ 
					'sub_category_id_pk' => $id 
			] );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	public function getSubCategories(Request $request) {
		try {
			return $this->manageCategory->getSubCategories ( [ ] );
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
				'category_id_fk' => 'required|exists:categories,category_id_pk',
				'name' => 'required',
				'status' => 'required',
				'color' => 'required',
				'area_id_fk' => 'required|exists:areas,area_id_pk' 
		
		], [ 
				'required' => 'The :attribute is required.',
				'category_id_fk.required' => 'Select category.',
				'area_id_fk.required' => 'Select area.',
				'category_id_fk.exists' => 'Selected category not exists.',
				'area_id_fk.exists' => 'Selected area not exists.' 
		] );
	}
	public function statusSubCategoryUpdate(Request $request) {
		try {
			$status = $this->manageCategory->statusSubCategoryUpdate ( $request->all () );
			if ($status)
				return json_encode ( array (
						'status' => $status 
				) );
		} catch ( \Exception $e ) {
			abort ( 500 );
		}
	}
	public function saveSubCategory(Request $request) {
		/**
		 * Validate
		 */
		$this->validator ( $request->all () )->validate ();
		return $this->manageCategory->saveOrEditSubCategory ( $request->all () );
	}
	public function deleteSubCategory($encodedURI = null, Request $request) {
		try {
			$id = explode ( '=', $encodedURI ) [1];
			$status = $this->manageCategory->subCategoryDelete ( $id );
			if ($status)
				return json_encode ( array (
						'status' => $status 
				) );
		} catch ( \Exception $e ) {
			abort ( 500 );
		}
	}
}
