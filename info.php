<?php
/**
 *
 * @category       modules
 * @package        ckeditor
 * @authors        WebsiteBaker Project, Michael Tenschert, Dietrich Roland Pehlke
 * @copyright      2009-2011, Website Baker Org. e.V.
 * @link           http://www.websitebaker2.org/
 * @license        http://www.gnu.org/licenses/gpl.html
 * @platform       WebsiteBaker 2.8.x
 * @requirements   PHP 5.2.2 and higher
 * @version        $Id: info.php 137 2012-03-17 23:29:07Z Luisehahne $
 * @filesource     $HeadURL: http://webdesign:8080/svn/ckeditor-dev/branches/info.php $
 *
 *
 * Changelog
 * 
 *  version 4.4.3 140727
 *  + function "show_wysiwyg_editor" accepts parameter "$toolbar" now to allow modules to use inidividual toolbars, thanks to dbs for suggesting
 *  ! using cke 4.4.3 full
 *  ! update skin moonocolor to v 1.3
 *  ! update wblink plugin to v 4.4.3
 *
 *  version 4.4.1.1 140528
 *  ! fixed wysiwyg-admin recognition
 *
 *  version 4.4.1 140527
 *  + using cke 4.4.1 full
 *  ! changed wysiwyg-admin recognition
 *
 *  version 4.4.0 140501
 *  + using cke 4.4.0 full
 *  ! wblink plugin uses the extended wblink function of wb284 now: http://www.websitebaker.org/forum/index.php/topic,25334.msg187246.html#msg187246
 *
 *  version 4.3.4 140404
 *  + using cke 4.3.4 full
 *  ! update codemirror plugin to v. 1.10
 *  ! update youtbue plugin to v. 1.09
 *
 *  version 4.3.3 140303
 *  + using cke 4.3.3 full
 *  ! update wblink plugin
 *  ! because of consecutive problems wblink plugin allows now only a defined set of characters for page item selection
 *
 *  version 4.3.2 140131
 *  + using cke 4.3.2 full
 *  ! bug in wbsave plugin
 *  ! bug in wblink plugin reported by instantflorian and jacobi22 http://www.websitebaker.org/forum/index.php/topic,25334.msg184283.html#msg184283
 *
 *  version 4.3.1.2 140110
 *  ! filemanager works with IE11 now, reported by argos: http://www.websitebaker.org/forum/index.php/topic,17913.msg182267.html#msg182267
 *
 *  version 4.3.1.1 131219
 *  ! editor was not attached to textarea named "description", reported by bsdzilla: http://www.websitebaker.org/forum/index.php/topic,17913.msg182267.html#msg182267
 *
 *  version 4.3.1 131211
 *  + using cke 4.3.1 full
 *  ! again fixed problem in wb droplets plugin
 *  ! fixed problem in wb link plugin
 *  + bakery items are selectable as wb link, thanks to jacoby22: http://www.websitebaker.org/forum/index.php/topic,25334.msg181817.html#msg181817
 *	! wb link plugin: items select is only shown when a news, topis or bakery page is selected
 *
 *  version 4.3.0 131120
 *  + using cke 4.3.0 full
 *  ! wblink plugin can address news page without selecting a news item
 *  ! fixed problem in wb droplets plugin
 *
 *  version 4.2.2 131024
 *  + using cke 4.2.2 full
 *  ! updated moonocolor skin to v 1.1 (1.2 didn't work for some reasons)
 *
 *  version 4.2.1 131001
 *  + using cke 4.2.1 full
 *  ! message bug in backup plugin
 *  ! updated youtube plugin to v. 1.0.7
 *
 *  version 4.2.0.1 130801
 *  ! fixed filemanager for IE 10 as reported by argos http://www.websitebaker.org/forum/index.php/topic,25334.msg183193.html#msg183193
 *  ! corrected version of cke in module description an editor.php
 *
 *  version 4.2.0 130724
 *  + using cke 4.2.0 full
 *  ! changed wb default toolbar for better overview and to avoid double source button
 *  ! updated codemirror plugin to v. 1.06
 *  + added patched/extended version of backup plugin using local browser storage
 *  ! replaced editor save plugin by wbsave which saves without page reload
 * 
 *  version 4.1.2 130616
 *  + using cke 4.1.2 full
 *  + added youtube plugin as requested by Bug: http://www.websitebaker.org/forum/index.php/topic,25334.msg176059.html#msg176059
 *  + added oembed plugin
 *
 *  version 4.1.1 130430
 *  + using cke 4.1.1 full
 *  ! disabled Advanced Content Filter (ACF) to prevent filtering of wblinks
 *  ! excluded loading of link plugin because of double entries in context menu, must be loaded for non WB Toolbars
 *  + topics pages are also selectable as wblink as requested by instantflorian http://www.websitebaker.org/forum/index.php/topic,25334.msg175956.html#msg175956
 * 
 *  version 4.1.0 130429
 *  + using cke 4.1.0 full
 *  + moonocolor skin as suggested by jacobi22 http://www.websitebaker.org/forum/index.php/topic,25334.msg175711.html#msg175711 (icons for wb plugins need redesign)
 *  ! fixed language problems as reported and solved by jacobi22 http://www.websitebaker.org/forum/index.php/topic,25334.msg175684.html#msg175684
 *    and nibz http://www.websitebaker.org/forum/index.php/topic,25334.msg175692.html#msg175692
 *  - deleted examples folder to keep file size a least bit smaller
 *  ! some more code cleaning, make toolbar selection work with wysiwyg-admin http://www.websitebakers.com/pages/admin/admin-tools/wysiwyg-admin.php
 *
 *  version 4.0.1 130408
 *  + wb plugins patched for cke 4.0.1
 *  ! some code cleaning
 *
 *  version 4.0.1 130117
 *  initial release using cke 4.0.1 full heavily based on version 0.7x with cke 3.x
 *  without wb plugins
 *
 */

/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {

	require_once(dirname(dirname(dirname(__FILE__))).'/framework/globalExceptionHandler.php');
	throw new IllegalFileException();
}
/* -------------------------------------------------------- */

$module_directory	= 'ckeditor';
$module_name			= 'CKEditor';
$module_function	= 'WYSIWYG';
$module_version		= '4.4.3';
$module_platform	= '2.8.3';
$module_author		= 'Michael Tenschert, Dietrich Roland Pehlke, erpe, Luisehahne.';
$module_license		= '<a target="_blank" href="http://www.gnu.org/licenses/lgpl.html">LGPL</a>';
$module_description = 'includes CKEditor 4.4.3, CKE allows editing content and can be integrated in frontend and backend modules.';
