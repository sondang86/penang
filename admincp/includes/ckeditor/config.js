/*

Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/


CKEDITOR.editorConfig = function( config )

{

    config.toolbar = 'MyToolbar';
    config.toolbar_MyToolbar =

    [

    ['Undo','Redo'],
	['FontSize'],	
	['Bold','Italic','Underline','TextColor','BGColor'],
	['NumberedList','BulletedList'],
        ['Link','Unlink','Image','MediaEmbed'],
        ['Source']

    ];

config.extraPlugins = 'MediaEmbed';
config.extraPlugins = 'confighelper';
};

CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;


