var Dropzone;
var initDataLoadingFlag;
var maxFileExceededFlag;
var cropFlag;
$(function() {

	// Global Flags set to false
	initDataLoadingFlag = false;
	maxFileExceededFlag = false;
	cropFlag = true;
});

window.uploadImage = function(datar){
	if(!cropFlag){
		var formData = new FormData();
		formData.append('image', datar.image);
		formData.append('dir', datar.dir);
		
		$.ajax({
			url: '/images/upload',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'JSON',
			success: function (data) {
				cropFlag = false;
				$('#image_status').val(true);
				$('#image').val(data.id);
				$('#image_ref').val('cache');
				
				alertService({
					title: 'Success !',
					text: "Image Uploaded Successfully.",
					type: 'success',
				});
			},
			error: function (data) {
				var res = data.responseJSON;
	    		serverErrorHandler(res);
			}
		});
	}
}

window.initDropZone = function(params){
	Dropzone.autoDiscover = false;

    $(params.class).dropzone({
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 4, // MB
        addRemoveLinks: true,
        acceptedFiles: 'image/jpeg,image/png',
        maxFiles: 1,
        url: '#',
        dictDefaultMessage: 'Drop file to upload <span>or CLICK</span>',
        autoProcessQueue: false,
        init: function() {
            Dropzone = this;

            this.on('thumbnail', function(file){
            	cropFlag = true;
            	if(!initDataLoadingFlag) {
            		console.log('cropFlag >> '+cropFlag);
        	    
            	   	if (file.cropped) {
            	    	cropFlag = true;
            	        return;
            	    }
//            	    if (file.width < 627) {
//            	    	cropFlag = false;
//            	        return;
//            	    }

            	    var cachedFilename = file.name;
            	    this.removeFile(file);
            	    var $cropperModal = $('#modal_img_crop');
            	    var $uploadCrop = $cropperModal.find('.crop-upload');

            	    var $img = $('<img width="100%"/>');
            	    var reader = new FileReader();
            	    reader.onloadend = function () {
            	        $cropperModal.find('.image-container').html($img);
            	        $img.attr('src', reader.result);

            	        $img.cropper({
            	            aspectRatio: 2.49 / 4,
            	            autoCropArea: 1,
            	            movable: false,
            	            cropBoxResizable: true,
            	            minContainerWidth: 850,
            	            minContainerHeight: 425
            	        });
            	    };
            	    reader.readAsDataURL(file);
            	    $cropperModal.modal('show');
            		
            	    $uploadCrop.on('click', function(e) {
            	    	e.preventDefault();
            	    	
            	        $img.cropper('getCroppedCanvas').toBlob(
            	        	function (blob) {
            	        			 console.log('--------------image croped------------');
            	        			 blob.cropped = true;
            	        			 blob.name = cachedFilename;
            	        			 Dropzone.addFile(blob);
            	            	     $cropperModal.modal('hide');
            	        	},
            	        	'image/jpeg',
            	        	0.75
            	        );
            	        
            	    	var image = new Image();
            	    	image.src = $img.cropper('getCroppedCanvas',{
            	    		imageSmoothingEnabled: true,
            	    		imageSmoothingQuality: 'low'
            	    	}).toDataURL("image/jpeg") ;

            	    	cropFlag = false;
                    	uploadImage({image: image.src, dir: params.dir});
            	    });
            	}else{
            		cropFlag = false;
            	}
            });
            
            this.on('addedfile', function(file){
            	if (this.files[1]!=null){
                    this.removeFile(this.files[0]);
                }
            	if(!initDataLoadingFlag) {
                	removeErrorPlacement('.dropzone');
            	}
            });
            
            this.on("maxfilesexceeded", function(file){
            	maxFileExceededFlag = true;
            	console.log('maxFileExceededFlag >> '+maxFileExceededFlag);
                this.removeFile(file);
                
            	setTimeout(function(){
            		maxFileExceededFlag = false;
                	console.log('maxFileExceededFlag >> '+maxFileExceededFlag);
            	},300);
            	
				alertService({
					title: 'Warning !',
					text: "Maximum number of images allowed exceeded.",
					type: 'warning',
				});
            });

            this.on("removedfile", function (file){
//            	if(!maxFileExceededFlag && !cropFlag){
            	if(!cropFlag){
                	var formDataRemove = new FormData();
                	formDataRemove.append('image', $('#image').val());
                	formDataRemove.append('dir', params.dir);
                	formDataRemove.append('imageRef', $('#image_ref').val());
                	
                	removeErrorPlacement('.dropzone');
                	
                	$.ajax({
            			url: '/images/remove',
            			type: 'POST',
            			data: formDataRemove,
            			processData: false,
            			contentType: false,
            			dataType: 'JSON',
            			success: function (data) {
            				alertService({
            					title: 'Success !',
            					text: "Image Removed Successfully.",
            					type: 'success',
            				});
            			}
                	});
                	$('#image_status').val(false);
                	$('#image').val('');
            	}
            	Dropzone.options.maxFiles = 1;
            });
            
            this.on('success', function(file, response) {
            	
            });
        }
    });
}