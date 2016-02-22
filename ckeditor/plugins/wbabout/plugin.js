/*
Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
( function()
{
    CKEDITOR.plugins.add( 'wbabout',
    {
        requires: 'dialog',
        lang :
        [
            CKEDITOR.config.defaultLanguage,
            CKEDITOR.lang.detect(CKEDITOR.config.language )
        ],
        icons: 'wbabout', // %REMOVE_LINE_CORE%
        hidpi: true // %REMOVE_LINE_CORE%
//     lang : ['en','de','nl'],
    }, //lang
    {
        init : function( editor )
        {
            // It doesn't add commands, buttons or dialogs, it doesn't do anything here
//  console.log( editor);
        } //Init
    } );

    CKEDITOR.on( 'dialogDefinition', function( evt )
    {
        var dialog = evt.data,
            dialogName = dialog.name,
            dialogDefinition = dialog.definition,
            editor = evt.editor,
            lang = editor.lang.wbabout,
            wblogoPath = CKEDITOR.plugins.get( 'wbabout' ).path + 'icons/' + ( CKEDITOR.env.hidpi ? 'hidpi/' : '' ) + 'powered.png';
        var ua   = navigator.userAgent;
        var ie   = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})"),
            ie11 = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    // editor.on( 'commit', function(ev) { console.log( ev ) });
        if ( (dialogName == 'about') )
        {
            var linkTab = dialogDefinition.getContents('tab1');

            linkTab.title = CKEDITOR.env.ie ? lang.dlgTitle : lang.title,
            linkTab.label = lang.labelTitle;
console.info(linkTab);
        // Get dialog definition.
            var def = evt.data.definition;
        // Add some stuff to definition.
            def.addContents( {
                id: 'WebsiteBaker',
                label: lang.wbLabelTitle,
                title: '',
                padding: 10,
                elements: [
                    {
                    type: 'html',
                    html: '<style type="text/css">' +
                        '.cke_about_container' +
                        '{' +
                            'color:#000 !important;' +
                            'padding:10px 10px 0;' +
                            'margin-top:5px' +
                            'max-width:' + '390px;' + 
                            '' +
                        '}' +
                        '.cke_about_container p' +
                        '{' +
                            'margin: 0 0 10px;' +
                        '}' +
                        '.cke_about_container .cke_wbabout_logo' +
                        '{' +
                            'height:80px;' +
                            'background-color:#fff;' +
                            'background-image:url(' + wblogoPath + ');' +
                            ( CKEDITOR.env.hidpi ? 'background-size:163px 58px;' : '' ) +
                            'background-position:center; ' +
                            'background-repeat:no-repeat;' +
                            'margin-bottom:10px;' +
                        '}' +
                        '.cke_about_container a' +
                        '{' +
                            'cursor:pointer !important;' +
                            'color:#00B2CE !important;' +
                            'text-decoration:underline !important;' +
                        '}' +
                        '</style>' +
                        '<div class="cke_about_container">' +
                        '<div class="cke_wbabout_logo"></div>' +
                        '<p>' + 
                            lang.wbModuleinfo.replace( '$1', editor.config.WBversion) + editor.config.WBrevision +') ' + 
                        '</p>' +
                        '<p>' +
                            lang.wbModulversion.replace( '$1', editor.config.ModulVersion) + editor.config.ModulVersion +' ' + 
                        '</p>' +
                        '<p>' +
                            lang.wbHelp.replace( '$1', '<a href="http://websitebaker.org/">' + lang.wbUserGuide + '</a>' ) +
                        '</p>' +
                        '<p>' +
                            lang.wbMoreInfo.replace( '$1', '<a href="http://websitebaker.org/">' + lang.wbUserGuide + '</a>' ) +
                        '</p>' +
                        '<p>' +
                            lang.wbCopy.replace( '$1', '<a href="http://websitebaker.org/">WebsiteBaker Org e.V.</a> - WebsiteBaker Project' ) +
                        '</p>' +
                        '</div>'
                }
                ]
            });
            
        } // if ( (dialogName == 'about')
    });
})();
