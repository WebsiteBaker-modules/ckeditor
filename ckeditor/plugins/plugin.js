/**
 *
 * @category        ckeditor
 * @package         image
 * @author          WebsiteBaker Project, Luisehahne
 * @copyright       2009-2012, WebsiteBaker Org. e.V.
 * @link            http://www.websitebaker.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.9.x
 * @requirements    PHP 5.2.2 and higher
 * @version         $Id: plugin.js 137 2012-03-17 23:29:07Z Luisehahne $
 * @filesource      $HeadURL: http://webdesign:8080/svn/ckeditor-dev/branches/ckeditor/_source/plugins/relation/plugin.js $
 * @lastmodified    $Date: 2012-03-18 00:29:07 +0100 (So, 18. Mrz 2012) $
 *
 */
( function()
{
    CKEDITOR.plugins.add( 'relation',
	{
		lang :
		[
			CKEDITOR.config.defaultLanguage,
			CKEDITOR.lang.detect(CKEDITOR.config.language )
		]
//     lang : ['en','de','nl'],
	}, //lang
	{
		init : function( editor )
		{
			// It doesn't add commands, buttons or dialogs, it doesn't do anything here
// 				console.log( editor);
		} //Init

	} );
//		editor.on( 'change', function(e) { console.log( e ) });

    CKEDITOR.on( 'dialogDefinition', function( ev )
    {
    	var dialogName = ev.data.name;
    	var dialogDefinition = ev.data.definition;
    	var editor = ev.editor;
	// editor.on( 'commit', function(ev) { console.log( ev ) });

		function var_dump(obj) {
// console.log( obj ) ;
			if(typeof obj == "object") {
				return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + obj;
			} else {
				return "Type: "+typeof(obj)+"\nValue: "+obj;
			}
		}

       	function getSelectedLink( editor )
       	{
       		try
       		{
       			var selection = editor.getSelection();
       			if ( selection.getType() == CKEDITOR.SELECTION_ELEMENT )
       			{
       				var selectedElement = selection.getSelectedElement();
       				if ( selectedElement.is( 'a' ) )
       					return selectedElement;
       			}

       			var range = selection.getRanges( true )[ 0 ];
       			range.shrink( CKEDITOR.SHRINK_TEXT );
       			var root = range.getCommonAncestor();
       			return root.getAscendant( 'a', true );
       		}
       		catch( e ) { return null; }
       	}

    	if ( dialogName == 'image' )
    	{

            var linkTab = dialogDefinition.getContents('Link');
			// var link = getSelectedLink( editor );

            linkTab.add (
            {
    			type : 'vbox',
    			padding : 0,
    			children :
    			[

                    {
						type : 'hbox',
						widths : [ '40%', '60%' ],
	   					style : 'margin-top:10px;',
						children :
						[
							{
								type : 'text',
								label : editor.lang.relation.rel,
								'default' : '',
								id : 'cmbRel',
    							setup : function()
    							{
//    console.log( CKEDITOR.config.language ) ;
									var link = getSelectedLink( editor );
                                    if(link) {
        								this.setValue( link.getAttribute( 'rel' ) || '' );
                                        var d = CKEDITOR.dialog.getCurrent();
                                        var LinkField = d.getContentElement( 'Link', 'txtUrl' ),
                                            orgLinkField = LinkField.getValue(),
                                            RelObj = d.getContentElement( 'Link', 'cmbRel' );
                                        var RelValue = RelObj.getValue('cmbRel');

                                       if(orgLinkField != '' && RelObj.getValue('cmbRel') != '') {
                                        }
                                    }
    							},
                    			onChange : function ()
                    			{


                    			},
    							//commit : function( element )
					            commit : function( data, selectedElement )
								{
    								if ( this.getValue() != '' ) {
    									this.linkElement = this.getDialog().linkElement;
                                        this.linkElement.setAttribute( 'rel', this.getValue() );
    								}
								}

							},
							{
								type : 'text',
								label : editor.lang.relation.cssClasses,
								'default' : '',
								id : 'cmbClass',
    							setup : function()
    							{
									var link = getSelectedLink( editor );
									var element = editor.getSelection().getStartElement();
									var element = element.getParent();
									if ( element.getName() == 'a' )
									{
        								this.setValue( link.getAttribute( 'class' ) || '' );

                                        var d = CKEDITOR.dialog.getCurrent();
                                        var LinkField = d.getContentElement( 'Link', 'txtUrl' ),
                                            orgLinkField = LinkField.getValue(),
                                            TitleObj = d.getContentElement( 'Link', 'cmbClass' );
                                        if(orgLinkField != '' && TitleObj.getValue() != '') {
                                        }
                                    }
    							},
                    			onChange : function ()
                    			{
                    			},
    							//commit : function( element )
					            commit : function( data, selectedElement )
								{
    								if ( this.getValue() != '' ) {
    									this.linkElement = this.getDialog().linkElement;
                                        this.linkElement.setAttribute( 'class', this.getValue() );
    								}
								}

							}
						]
                    },

                    {
						type : 'hbox',
						widths : [ '50%', '50%' ],
	   					style : 'margin-top:10px;',
						children :
						[
							{
								type : 'text',
								label : editor.lang.relation.advisoryTitle,
								'default' : '',
								id : 'cmbTitle',
    							setup : function()
    							{
									var link = getSelectedLink( editor );
                                    if(link) {
        								this.setValue( link.getAttribute( 'title' ) || '' );

                                        var d = CKEDITOR.dialog.getCurrent();
                                        var LinkField = d.getContentElement( 'Link', 'txtUrl' ),
                                            orgLinkField = LinkField.getValue(),
                                            TitleObj = d.getContentElement( 'Link', 'cmbTitle' );
                                        if(orgLinkField != '' && TitleObj.getValue() != '') {
                                        }
                                    }
    							},
                        			onChange : function ()
                    			{
                    			},
							//commit : function( element )
					            commit : function( data, selectedElement )
								{
    								if ( this.getValue() != '' ) {
    									this.linkElement = this.getDialog().linkElement;
                                        this.linkElement.setAttribute( 'title', this.getValue() );
    								}
								}

							}
						]
                    },
        		]
            });
    	}
    });
})();


