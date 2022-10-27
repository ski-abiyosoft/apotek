 /*******************************************
 * filtype  : java script
 * filename : upload.js
 * author   : Rahmattullah( Omens)
 * Title    : demo upload by javascript	
 *****************************************/

var AjaxUploads = {


 /* config this class, with default parameter **/
	
	UploadsReady:function(e){},
	
	UploadsConfig:{
		actToUploads   : 'upload.php',
		methodUploads  : 'POST',
		fileToUploads  : 'fileToupload',
		numberProgress : 'progressNumber',
		innerProgress  : 'prog',
		
		fileInfoUploads: {
				fileName :'fileName',
				fileType :'fileType',
				fileSize :'fileSize',
		}
	},
	
/* get value by id object ***/
	
	UploadsGetID :function(e){
		if(e){
			var __id = document.getElementById(e);
			return __id;
		}
	},
	
  /* information file to upload **/
	
	UploadInfo : function(){
		var uploadFile = AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.fileToUploads).files[0];
			if (uploadFile) {
                var uploadFileSize = 0;
                if (uploadFile.size)
                    uploadFileSize = (Math.round(uploadFile.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
                else
                    uploadFileSize = (Math.round(uploadFile.size * 100 / 1024) / 100).toString() + 'KB';
				
				alert(uploadFileSize);
				AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.fileInfoUploads.fileName).innerHTML = 'Name: ' + uploadFile.name;
                AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.fileInfoUploads.fileType).innerHTML = 'Size: ' + uploadFileSize;
                AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.fileInfoUploads.fileSize).innerHTML = 'Type: ' + uploadFile.type;
               
            }
	},
	
/* action uploads file **/

	UploadsFile : function () {
        var formUploads = new FormData();
            formUploads.append(AjaxUploads.UploadsConfig.fileToUploads, AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.fileToUploads).files[0]);
            var doUploads = new XMLHttpRequest(); // ajax function 
				doUploads.upload.addEventListener("progress", AjaxUploads.UploadsProgress, false);
				doUploads.addEventListener("load", AjaxUploads.UploadsComplete, false);
				doUploads.open(AjaxUploads.UploadsConfig.methodUploads, AjaxUploads.UploadsConfig.actToUploads);
				doUploads.send(formUploads);
			
    },	
	
/* on progress upload **/	

	UploadsProgress : function(evt) { // evt is object :
	/*
		********************************************************
		*	untuk melihat isi dari object evt
		*   gunakan :
		*	
		*	for( i in evt){
		*		alert('Object :'+i+',  content :'+evt[i]);
		*	}
		*
		**********************************************************
	*/
		
		if (evt.lengthComputable) {
                var percentComplete = Math.round(evt.loaded * 100 / evt.total);
                AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.numberProgress).innerHTML = percentComplete.toString() + '%';
                AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.innerProgress).value = percentComplete;
            }
            else {
                AjaxUploads.UploadsGetID(AjaxUploads.UploadsConfig.numberProgress).innerHTML = 'unable to compute';
            }
    },

	
/* if completed upload **/
	
	UploadsComplete :function(evt){
		alert(evt.target.responseText);
	}
}
