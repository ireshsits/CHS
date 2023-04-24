<?php

namespace App\Traits;

use Storage;
use Image;
use ImageOptimizer;
use Config;
use DateHelper;
use Cache;

trait ImageService
{
	private function put(array $data) {
		return Storage::disk ( 'public' )->put ( $data ['image_path'], $data ['content'] );
	}
	private function url($path) {
		if ($this->checkImage ( $path ))
			return Storage::disk ( 'public' )->url ( $path );
		return null;
	}
	private function deleteImageFromPath($path) {
		return Storage::disk ( 'public' )->delete ( $path );
	}
	private function checkImage($path) {
		return Storage::disk ( 'public' )->has ( $path );
	}
	
	// -------------------------------------------------------------------------
	// create image path
	private function createImagePath(array $data) {
		return 'images/' . $data ['dir'] . '/' . $data ['image_name'];
	}
	// public function create image name for saving
	private function getImageName($data) {
		
		try {
			$extension = $data ['image']->getClientOriginalExtension ();
		} catch ( \Exception $e ) {
			$extension = $data ['image']->extension;
		}
		switch ($data ['dir']) {
			case 'books' :
				return 'book-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			case 'magazines' :
				return 'magazine-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			case 'newspapers' :
				return 'newspaper-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			case 'book_types' :
				return 'book_type-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			case 'magazine_types' :
				return 'magazine_type-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			case 'newspaper_types' :
				return 'newspaper_type-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			case 'school_grades' :
				return 'school_grade-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			case 'users' :
				return 'user-' . sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
			default :
				return sha1 ( DateHelper::getNow () . str_random ( 30 ) ) . '.' . $extension;
				break;
		}
	}
	// get url
	private function getImageUrl($path) {
		return $this->url ( $path );
	}
	// process image data for upload
	private function process_image_data(array $data) {
		try {
			$extension = $data ['image']->getClientOriginalExtension ();
			$path = $data ['image']->path ();
		} catch ( \Exception $e ) {
			$extension = $data ['image']->extension;
			$path = $data ['image']->dirname . '\\' . $data ['image']->basename;
		}
		$data ['image_name'] = $this->getImageName ( $data );
		$imagePath = $this->createImagePath ( $data );
		$originalImagePath = $path;
		$extension = $extension;
		ImageOptimizer::optimize ( $originalImagePath );
		return array (
				"image_name" => $data ['image_name'],
				"image_path" => $imagePath,
				"content" => file_get_contents ( $originalImagePath ),
				"extension" => $extension 
		);
	}
	// Create Default image
	private function getDefaultImagePath($data) {
		switch ($data ['dir']) {
			case 'books' :
				return 'images/books/default.png';
				break;
			case 'magazines' :
				return 'images/magazines/default.png';
				break;
			case 'newspapers' :
				return 'images/newspapers/default.png';
				break;
			case 'book_types' :
				return 'images/book_types/default.png';
				break;
			case 'magazine_types' :
				return 'images/magazine_types/default.png';
				break;
			case 'newspaper_types' :
				return 'images/newspaper_types/default.png';
				break;
			case 'school_grades' :
				return 'images/school_grades/default.png';
				break;
			case 'users' :
				return 'images/users/default.png';
				break;
			default :
				break;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------------
	
	// upload image
	private function putImage($data) {
		return $this->put ( $data );
	}
	private function putMinifiedImage($data) {
		// resize the image to a width of 300 and constrain aspect ratio (auto height)
		$image = Image::make ( $data ['content'] )->resize ( Config ( 'image.width' ), Config ( 'image.height' ), function ($constraint) {
			$constraint->aspectRatio ();
		} );
		$data ['content'] = $image->response ( $data ['extension'] )->content ();
		return $this->put ( $data );
	}
	private function decodeToJPG($imgr) {
		$img = str_replace ( 'data:image/jpeg;base64,', '', $imgr );
		$img = str_replace ( ' ', '+', $img );
		$data = base64_decode ( $img );
		$file = sys_get_temp_dir () . '\\' . uniqid () . '.jpg';
		$success = file_put_contents ( $file, $data );
		return $success ? Image::make ( $file ) : $imgr;
	}
	public function saveImage(array $data) {
		try {
			$data ['image'] = $this->decodeToJPG ( $data ['image'] );
			$processedData = $this->process_image_data ( $data );
			// $imageStatus = $this->putMinifiedImage ( $processedData );
			$imageStatus = $this->putImage ( $processedData );
			if ($imageStatus) {
				$arr = array (
						// "id" => $data ['id'],
						"image_url" => $this->url ( $processedData ['image_path'] ),
						"image_path" => $processedData ['image_path'],
						"image_status" => $imageStatus,
						"image_name" => $processedData ['image_name'] 
				);
				
				if ($data ['cacheData']) {
					return $this->setCacheDetails ( $arr );
				}
				
				if ($data ['saveInDB']) {
					return $data ['object']->saveOrEditImage ( $arr );
				}
				
				return $arr;
			}
		} catch ( \Exception $e ) {
			return array (
					'status' => false,
					'response' => $e 
			);
		}
	}
	public function getdefaultImage($data) {
		try {
			return $this->getImageUrl( $this->getDefaultImagePath ( $data ) );
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public function getImage($data) {
		try {
			return $this->getImageUrl ( $this->createImagePath ( $data ) );
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public function getImageStatus($data) {
		if (! empty ( $data ['image_name'] ))
			return $this->checkImage ( $this->createImagePath ( $data ) );
		return false;
	}
	public function deleteImage(array $data) {
		try {
			$data ['image_name'] = $this->getImageNameByRef ( $data );
			$imagePath = $this->createImagePath ( $data );
			$imageStatus = $this->deleteImageFromPath ( $imagePath );
			if ($imageStatus) {
				return array (
						'status' => true 
				);
			}
		} catch ( \Exception $e ) {
			return array (
					'status' => false,
					'response' => $e 
			);
		}
	}
	public function getImageNameByRef($data) {
		if ($data ['imageRef'] == 'file') {
			return $data ['image'];
		} else {
			return $this->getCacheDetails ( $data );
		}
	}
	public function getCacheDetails($data) {
		$cache = Cache::get ( $data ['image'] );
		return $cache ['image_name'];
	}
	public function setCacheDetails($data) {
		$imageId = sha1 ( DateHelper::getNow () . str_random ( 30 ) );
		Cache::put ( $imageId, $data, 120 );
		return $imageId;
	}
}