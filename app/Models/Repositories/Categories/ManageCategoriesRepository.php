<?php

namespace App\Models\Repositories\Categories;

use DateHelper;
use UserHelper;
use App\Models\Entities\Category;
use App\Models\Entities\SubCategory;
use App\Models\Entities\Setting;
use DataTables;

class ManageCategoriesRepository implements CategoryInterface {
	public function __construct() {
	}
	private function generateSearchQuery($data) {
		$query = Category::select ( '*' );
		if(isset($data['name']))
			$query->whereRaw ( 'LOWER(name) like ?', array (
					'name' => '%' . strtolower ( $data ['name'] ) . '%' 
			) );
		/**
		 * CR3 change
		 * Get the maximum category level for the raise page
		 * 
		 */
		if(isset($data['parent_category_id']))
		    $query->where('parent_category_id',$data['parent_category_id']);
		
		if(isset($data['level']))
		  $query->whereRaw('category_level = ?',[$data['level']]);
		else
		  $query->where('category_level',Setting::getCategorySettings()->RAISE_LEVEL);
		return $query->where('status','ACT')->distinct ();
	}
	private function generateQuery($filters) {
		$query = Category::select ( '*' );
		/**
		 * Changed in CR3
		 */
// 		if (isset ( $filters ['subCategoryFilters'] )) {
// 			$query->WhereHas ( 'subCategories', function ($q) use ($filters) {
// 				if (isset ( $filters ['sub_category_id_pk'] ))
// 					$q->where ( 'sub_category_id_pk', $filters ['sub_category_id_pk'] );
// 			} );
// 		}
		$query->with ( [ 
// 				'subCategories',
                'parentCategory',
		        'rejectedBy'
		] );
		
		if (isset ( $filters ['category_id_pk'] ))
			$query->where ( 'category_id_pk', $filters ['category_id_pk'] );
		if (isset ( $filters ['random'] ))
			$query->inRandomOrder ();
		if (isset ( $filters ['limit'] ))
			$query->limit ( $filters ['limit'] );
		return $query;
	}
// 	private function generateSubSearchQuery($data) {
// 		$query = SubCategory::select ( '*' );
// 		if (isset( $data ['name'] ) > 1)
// 			$query->whereRaw ( 'LOWER(name) like ?', array (
// 					'name' => '%' . strtolower ( $data ['name'] ) . '%' 
// 			) );
// 		$query->where ( 'category_id_fk', $data ['resource_id'] )->distinct ();
// 		return $query->where('status','ACT')->distinct();
// 	}
// 	private function generateSubQuery($filters) {
// 		$query = SubCategory::select ( '*' );
// 		if (isset ( $filters ['areaFilters'] )) {
// 			$query->WhereHas ( 'area', function ($q) use ($filters) {
// 				if (isset ( $filters ['area_id_pk'] ))
// 					$q->where ( 'area_id_pk', $filters ['area_id_pk'] );
// 			} );
// 		}
// 		$query->with ( [ 
// 				'area',
// 		        'rejectedBy'
// 		] );
		
// 		if (isset ( $filters ['sub_category_id_pk'] ))
// 			$query->where ( 'sub_category_id_pk', $filters ['sub_category_id_pk'] );
// 		if (isset ( $filters ['random'] ))
// 			$query->inRandomOrder ();
// 		if (isset ( $filters ['limit'] ))
// 			$query->limit ( $filters ['limit'] );
// 		return $query;
// 	}
	private function buildSearchResponse($category) {
		$category->id = $category->category_id_pk;
		$category->text = $category->name;
		$category->display_category = true;
		return $category;
	}
	private function buildResponse($category) {
		return $category;
	}
// 	private function buildSubSearchResponse($category) {
// 		$category->id = $category->sub_category_id_pk;
// 		$category->text = $category->name;
// 		$category->display_sub_category = true;
// 		return $category;
// 	}
// 	private function buildAreaSearchResponse($area) {
// 		$area->id = $area->area_id_pk;
// 		$area->text = $area->name;
// 		$area->display_area = true;
// 		return $area;
// 	}
// 	private function buildSubResponse($subCategory) {
// 		return $subCategory;
// 	}
	/**
	 *
	 * @param unknown $filters        	
	 * @return unknown
	 */
	public function getCategory($filters) {
		$queryString = $this->generateQuery ( $filters );
		$category = $queryString->first ();
		return $this->buildResponse ( $category );
	}

	/**
	 *
	 * @param unknown $filters        	
	 * @return unknown
	 */
	public function getCategories($filters) {
		$queryString = $this->generateQuery ( $filters );
		$datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($category) {
			return $category->category_id_pk;
		} )->addColumn ( 'rejected_by', function ($category) {
			return (isset($category->rejectedBy) ? $category->rejectedBy->first_name.' '.$category->rejectedBy->last_name : '');
		} )
		/**
		 * Changed in CR3
		 */
// 		->addColumn ( 'sub_category_count', function ($category) {
// 			return count ( $category->subCategories);
// 		} )
	    ->addColumn ('parent_category', function($category) {
	        return $category->parentCategory? $category->parentCategory->name : '';
	    })->editColumn ( 'created_at', function ($complaint) {
			return DateHelper::formatToDateString ( $complaint->created_at );
		} )->removeColumn ( 'rejected_by_fk');
		
		return $datatable->toJson ();
	}
// 	/**
// 	 *
// 	 * @param unknown $filters
// 	 * @return unknown
// 	 */
// 	public function getSubCategories($filters) {
// 		$queryString = $this->generateSubQuery ( $filters );
// 		$datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($subCategory) {
// 			return $subCategory->sub_category_id_pk;
// 		} )->addColumn ( 'rejected_by', function ($subCategory) {
// 			return (isset($subCategory->rejectedBy) ? $subCategory->rejectedBy->first_name.' '.$subCategory->rejectedBy->last_name : '');
// 		} )->addColumn ( 'sub_category_name', function ($subCategory) {
// 			return $subCategory->name;
// 		} )->addColumn ( 'category_name', function ($subCategory) {
// 			return $subCategory->category->name??'';
// 		} )->addColumn ( 'area_name', function ($subCategory) {
// 			return $subCategory->area->name??'';	
// 		} )->editColumn ( 'created_at', function ($complaint) {
// 			return DateHelper::formatToDateString ( $complaint->created_at );
// 		} )->removeColumn ( 'rejected_by_fk');
		
// 		return $datatable->toJson ();
// 	}
	/**
	 * 
	 * @param unknown $params
	 * @return unknown
	 */
	public function saveOrEditCategory($data){
		if($data['status'] == 'REJ')
			$data['rejected_by_fk'] = UserHelper::getLoggedUserId();
		try {
			$filter = array ();
			if (isset ( $data ['mode'] )) {
				if ($data ['mode'] !== 'edit') {
					$filter = array (
							'name' => $data ['name']
					);
				} else {
					$filter = array (
							'category_id_pk' => $data ['id']
					);
				}
			}
			$category = Category::firstOrNew ( $filter );
			$category->fill ( $data );
			$category->save ();
			return $category;
		} catch ( \Exception $e ) {
			dd ( $e );
		}
	}
	/**
	 *
	 * @param unknown $filters        	
	 * @return unknown
	 */
	public function getCategoriesForSearch($filters) {
		$queryString = $this->generateSearchQuery ( $filters );
		$response = $queryString->get ();
		foreach ( $response as $category ) {
			$category = $this->buildSearchResponse ( $category );
		}
		return $response;
	}
	/**
	 *
	 * @param unknown $filters        	
	 * @return unknown
	 */
// 	public function getSubCategory($filters) {
// 		$queryString = $this->generateSubQuery ( $filters );
// 		$subCategory = $queryString->first ();
// 		return $this->buildSubResponse ( $subCategory );
// 	}
	
	/**
	 *
	 * @param unknown $params
	 * @return unknown
	 */
// 	public function saveOrEditSubCategory($data){
// 		if($data['status'] == 'REJ')
// 			$data['rejected_by_fk'] = UserHelper::getLoggedUserId();
// 		try {
// 			$filter = array ();
// 			if (isset ( $data ['mode'] )) {
// 				if ($data ['mode'] !== 'edit') {
// 					$filter = array (
// 							'name' => $data ['name']
// 					);
// 				} else {
// 					$filter = array (
// 							'sub_category_id_pk' => $data ['id']
// 					);
// 				}
// 			}
// 			$subCategory = SubCategory::firstOrNew ( $filter );
// 			$subCategory->fill ( $data );
// 			$subCategory->save ();
// 			return $subCategory;
// 		} catch ( \Exception $e ) {
// 			dd ( $e );
// 		}
// 	}
	/**
	 *
	 * @param unknown $filters        	
	 * @return unknown
	 */
// 	public function getSubCategoriesForSearch($filters) {
// 		$queryString = $this->generateSubSearchQuery ( $filters );
// 		$response = $queryString->get ();
// 		foreach ( $response as $category ) {
// 			$category = $this->buildSubSearchResponse ( $category );
// 		}
// 		return $response;
// 	}
	public function categoryDelete($id) {
		$category = Category::withTrashed ()->where ( 'category_id_pk', $id )->first ();
		if ($category->trashed ()) {
			return true;
		}
		return $category->delete ();
	}
// 	public function subCategoryDelete($id) {
// 		$subCategory = SubCategory::withTrashed ()->where ( 'sub_category_id_pk', $id )->first ();
// 		if ($subCategory->trashed ()) {
// 			return true;
// 		}
// 		return $subCategory->delete ();
// 	}
	public function statusCategoryUpdate($data) {
		if((int) $data['id'] > 0)
			$categories = Category::where ( 'category_id_pk', $data ['id'] )->get ();
		else
			$categories = Category::all();
		foreach ($categories as $category){
			if ($data ['field'] == 'status'){
				$category->status = $data ['status'];
			}
			$category->save ( $data );
		}
		return true;
	}
// 	public function statusSubCategoryUpdate($data) {
// 		if ((int) $data['id'] > 0)
// 		     $subCategories = SubCategory::where ( 'sub_category_id_pk', $data ['id'] )->get ();
// 		else 
// 			 $subCategories = SubCategory::all();
// 		foreach($subCategories as $subCategory){
// 			if ($data ['field'] == 'status'){
// 				$subCategory->status = $data ['status'];
// 			}
// 			$subCategory->save ($data);
// 		}
		
// 		return true;
// 	}
}