/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here.
    // For the complete reference:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config
    config.toolbar = [
        [ 'Source' ]
    ];

    config.toolbar_Basic = [
        [ 'Source', '-', 'Bold' ]
    ];

    config.toolbar_News = [
        [ 'Source', '-','Link']
    ];
    // Remove some buttons, provided by the standard plugins, which we don't
    // need to have in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript,';
    //config.toolbar = 'Basic';
};