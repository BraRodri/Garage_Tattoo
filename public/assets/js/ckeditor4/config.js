/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	var route = CKEDITOR.plugins.getPath('fontawesome');
	route = route.replace('ckeditor4/plugins/fontawesome/', '');
	
	config.toolbar = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
		{ name: 'links', items: [ 'Link', 'Unlink' ] },
		{ name: 'insert', items: [ 'Image', 'youtubebootstrap', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak', 'FontAwesome' ] },
		'/',
		{ name: 'styles', items: [ 'Format' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
		{ name: 'others', items: [ 'btgrid' ] }
	];
	
	config.extraPlugins = 'fontawesome,btgrid,youtubebootstrap';
	config.contentsCss = CKEDITOR.plugins.getPath('fontawesome') + 'font-awesome/css/font-awesome.min.css';
	config.allowedContent=true;	

	config.pasteFromWordRemoveFontStyles = false;
	config.pasteFromWordRemoveStyles = false;

	config.protectedSource.push( /<i class[\s\S]*?\>/g );
	config.protectedSource.push( /<\/i>/g );

	config.language = 'es';
	config.entities_latin = false;
	config.width = '100%';
	config.height = 300;
	config.resize_enabled = false;
	config.filebrowserBrowseUrl = route + 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&lang=es';
	config.filebrowserUploadUrl = route + 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&lang=es';
	config.filebrowserImageBrowseUrl = route + 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&lang=es';
};

CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;