var baseUrl = $('base').attr('href');
if(baseUrl.indexOf('admin/') == -1){
	baseUrl+='admin/';
	admin = false;	
}else{
	admin = true;
}
CKEDITOR.editorConfig = function( config ) {
	config.language = 'vi';
	config.skin = 'kama';
	config.height = 300;
	config.toolbarCanCollapse = true;
	if(admin){
		config.filebrowserUploadUrl = baseUrl + 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
		config.filebrowserBrowseUrl = baseUrl + 'plugins/ckfinder/ckfinder.html'; 
		config.filebrowserImageUploadUrl = baseUrl + 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
		config.filebrowserImageBrowseUrl = baseUrl + 'plugins/ckfinder/ckfinder.html?type=Images';
		config.filebrowserFlashBrowseUrl = baseUrl + 'plugins/ckfinder/ckfinder.html?type=Flash';
		config.filebrowserFlashUploadUrl = baseUrl + 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	}
};