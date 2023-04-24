<?php

namespace App\Traits;

use Storage;
use DateHelper;
use App\Models\Entities\Complaint;
use App\Models\Repositories\Complaints\ComplaintsRepository;

trait FileService
{
	private function fput(array $data) {
		return Storage::disk ( 'public' )->put ( $data ['file_path'], $data ['content'] );
	}
	private function furl($path) {
		if ($this->checkFile ( $path ))
			return Storage::disk ( 'public' )->url ( $path );
		return null;
	}
	private function fdeleteByPath($path) {
		return Storage::disk ( 'public' )->delete ( $path );
	}
	private function checkFile($path) {
		return Storage::disk ( 'public' )->has ( $path );
	}
	// ---------------------------------------------------------------------------------------------
	// create file path
	private function createFilePath(array $data) {
		return 'files/' . $data ['dir'] . '/' . $data ['file_name'];
	}
	// get url
	private function getFileUrl($path) {
		return $this->furl ( $path );
	}
	// upload image
	private function putFile($data) {
		return $this->fput ( $data );
	}
	// process file data for upload
	private function process_file_data(array $data) {
		try {
			$extension = $data ['file']->getClientOriginalExtension ();
			$path = $data ['file']->path ();
		} catch ( \Exception $e ) {
			$extension = $data ['file']->extension;
			$path = $data ['file']->dirname . '\\' . $data ['file']->basename;
		}
		$data ['file_name'] = $this->getFileName ( $data );
		$filePath = $this->createFilePath ( $data );
		$originalFilePath = $path;
		$extension = $extension;
		return array (
				"file_name" => $data ['file_name'],
				"file_path" => $filePath,
				"content" => file_get_contents ( $originalFilePath ),
				"extension" => $extension 
		);
	}
	// public function create file name
	private function getFileName($data) {
		try {
			$extension = $this->getFileExtension ( $data );
			/**
			 * Refernce number used as name
			 */
			$filename = $data['reference']?str_replace("|","",$data['reference']):$this->getClientOriginalFileName ( $data );
		} catch ( \Exception $e ) {
			$extension = explode ( '.', $data ['file'] ) [1];
			$filename = explode ( '.', $data ['file'] ) [0];
		}
		switch ($data ['dir']) {
			case 'complaints' : return $filename . '.' . $extension; break;
			default :return $filename . '.' . $extension; break;
		}
	}
	private function getFileExtension($data) {
		if (! is_string ( $data ['file'] )) {
			try {
				return ($data ['file'] !== null ? $data ['file']->getClientOriginalExtension () : explode ( '.', $data ['file'] ) [1]);
			} catch ( \Exception $e ) {
				return $data ['file']->extension;
			}
		} else {
			return explode ( '.', $data ['file'] ) [1];
		}
	}
	private function getClientOriginalFileName($data) {
		if (! is_string ( $data ['file'] )) {
			return pathinfo ( preg_replace ( '/\s+/', '', $data ['file']->getClientOriginalName () ), PATHINFO_FILENAME ) . '-' . DateHelper::getUnixTimeStamp ();
		} else {
			return explode ( '.', $data ['file'] ) [0];
		}
	}
	public function saveFile(array $data) {
		try {
		$processedData = $this->process_file_data ( $data );
		$fileStatus = $this->putFile ( $processedData );
		if ($fileStatus) {
			$arr = array (
					"reference" => $data ['reference'],
					"file_url" => $this->getFileUrl ( $processedData ['file_path'] ),
					"file_path" => $processedData ['file_path'],
					"file_status" => $fileStatus,
					"file_name" => $processedData ['file_name'],
					'file_type' => strtoupper($processedData['extension']),
					"object" => $data ['object']
			);
			if ($data ['saveInDB']) {
				return $this->saveFileInDB ( $arr );
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
	public function deleteFile(array $data) {
		try {
			$data ['file_name'] = $this->getFileName ( $data );
			$filePath = $this->createFilePath ( $data );
			$status= $this->fdeleteByPath ( $filePath );
			if($status){
				$arr = array (
						"reference" => $data ['reference'],
						"file_url" => null,
						"file_path" => null,
						"file_status" => false,
						"file_name" => null,
						'file_type' => null,
						"object" => $data ['object']
				);
				if ($data ['saveInDB']) {
					return $this->saveFileInDB ( $arr );
				}
				return $arr;
			}
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public function getFile($data) {
		try {
			return $this->getFileUrl ( $this->createFilePath ( $data ) );
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public function getFileStatus($data) {
		if (! empty ( $data ['file_name'] ))
			return $this->checkFile ( $this->createFilePath ( $data ) );
		return false;
	}
	private function saveFileInDB($data) {
		$objectRefClass =  $this->getObjectRefClass( $data );
	}
	private function getObjectRefClass($data) {
		switch ($data ['object']) {
			case 'Complaint' :
				$objectRefClass = new ComplaintsRepository(); 
				return $objectRefClass->saveOrEditAttachments(array(
						'complainId' => Complaint::where ('reference_number', $data ['reference'] )->first()->complaint_id_pk,
						'attachments' => [
								array(
										'attach_type' => $data['file_type'],
										'source' => $data['file_name']
								)
						]
				));
				break;
		}
	}

	public function getFileUrlWithCustom ($data) {

		try {
			$path = 'files/' . $data ['dir'] . '/' . $data ['file_name'];
			if ($this->checkFile ($path))
				return env('FILE_URL').'/storage/'.$path;
				// return Storage::disk('public')->url ($path);
			return null;
		} catch (\Exception $e) {
			return null;
		}

	}


}