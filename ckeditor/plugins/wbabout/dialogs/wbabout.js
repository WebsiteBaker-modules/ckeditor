/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.dialog.add( 'wbabout', function( editor ) {
	var lang = editor.lang.wbabout,
		imagePath  = CKEDITOR.plugins.get( 'wbabout' ).path + 'dialogs/' + ( CKEDITOR.env.hidpi ? 'hidpi/' : '' ) + 'logo_ckeditor.png',
		wblogoPath = CKEDITOR.plugins.get( 'wbabout' ).path + 'dialogs/' + ( CKEDITOR.env.hidpi ? 'hidpi/' : '' ) + 'logo_wb.png';
    var ua   = navigator.userAgent;
    var ie   = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})")
        ie11 = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})") ;


	return {
		title: CKEDITOR.env.ie ? lang.dlgTitle : lang.title,
		minWidth: 390,
		minHeight: 230,
		contents: [
			{
			id: 'ckeditor',
			label: lang.labelTitle,
			title: '',
			expand: true,
			padding: 0,
			elements: [
				{
				type: 'html',
				html: '<style type="text/css">' +
					'.cke_about_container' +
					'{' +
						'color:#000 !important;' +
						'padding:10px 10px 0;' +
						'margin-top:5px' +
					'}' +
					'.cke_about_container p' +
					'{' +
						'margin: 0 0 10px;' +
					'}' +
					'.cke_about_container .cke_about_logo' +
					'{' +
						'height:81px;' +
						'background-color:#fff;' +
						'background-image:url(' + imagePath + ');' +
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
					'<div class="cke_about_logo"></div>' +
					'<p>' +
						'CKEditor ' + CKEDITOR.version + ' (revision ' + CKEDITOR.revision + ')<br>' +
						'<a href="http://ckeditor.com/">http://ckeditor.com</a>' +
					'</p>' +
					'<p>' +
						lang.help.replace( '$1', '<a href="http://docs.ckeditor.com/user">' + lang.userGuide + '</a>' ) +
					'</p>' +
					'<p>' +
						lang.moreInfo + '<br>' +
						'<a href="http://ckeditor.com/about/license">http://ckeditor.com/about/license</a>' +
					'</p>' +
					'<p>' +
						lang.copy.replace( '$1', '<a href="http://cksource.com/">CKSource</a> - Frederico Knabben' ) +
					'</p>' +
					'</div>'
			}
			]
		}, //
{
			id: 'WebsiteBaker',
			label: lang.wbLabelTitle,
			title: '',
			padding: 0,
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
						'height:81px;' +
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
						lang.wbCopy.replace( '$1', '<a href="http://www.websitebaker.org/en/legal-notice-privacy-policy.php?lang=EN">WebsiteBaker Org e.V.</a> - WebsiteBaker Project' ) +
					'</p>' +
					'</div>'
			}
			]
		}
		],
 		buttons: [ CKEDITOR.dialog.cancelButton ]
	};
});
