/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
 function getRootPath(){
     var strPath=window.document.location.pathname;
     var postPath=strPath.substring(0,strPath.substr(1).indexOf('/')+1);
     return(postPath);
 }
CKEDITOR.config.extraPlugins += (CKEDITOR.config.extraPlugins ? ',lineheight' : 'lineheight');//行距③（转载）
CKEDITOR.config.extraPlugins += (CKEDITOR.config.extraPlugins ? ',image2,widget,lineutils,widgetselection,youtube' : 'image2,widget,lineutils,widgetselection');//圖片置中

// CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	if ( location.hostname  == 'localhost' ) {

		CKEDITOR.config.filebrowserBrowseUrl      = '//' +  window.location.hostname + '/Ian_Exclusive/public/admin/editor/ckfinder/ckfinder.html';
    	CKEDITOR.config.filebrowserImageBrowseUrl = '//' +  window.location.hostname + '/Ian_Exclusive/public/admin/editor/ckfinder/ckfinder.html?Type=Images';
		CKEDITOR.config.allowedContent=true;
		//
		CKEDITOR.config.youtube_related = false;
    	CKEDITOR.config.height = '400px';

	}else if(getRootPath() == '/demo'){
		CKEDITOR.config.filebrowserBrowseUrl      = '//' +  window.location.hostname + '/demo/public/admin/editor/ckfinder/ckfinder.html';
    	CKEDITOR.config.filebrowserImageBrowseUrl = '//' +  window.location.hostname + '/demo/public/admin/editor/ckfinder/ckfinder.html?Type=Images';
		CKEDITOR.config.allowedContent=true;
		//
		CKEDITOR.config.youtube_related = false;
    	CKEDITOR.config.height = '400px';

	}else{
		CKEDITOR.config.filebrowserBrowseUrl      = '//' +  window.location.hostname + '/public/admin/editor/ckfinder/ckfinder.html';
    	CKEDITOR.config.filebrowserImageBrowseUrl = '//' +  window.location.hostname + '/public/admin/editor/ckfinder/ckfinder.html?Type=Images';
		CKEDITOR.config.allowedContent=true;
		//
		CKEDITOR.config.youtube_related = false;
    	CKEDITOR.config.height = '400px';
	}
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.font_names ='Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Verdana/Verdana, Geneva, sans-serif;新細明體;標楷體;微軟正黑體' ;
// };
// alert(location.pathname);
