<?php
/**
 *
 * @category       modules
 * @package        ckeditor
 * @authors        WebsiteBaker Project, Michael Tenschert, Dietrich Roland Pehlke,D. Wöllbrink
 * @copyright      2009-2011, Website Baker Org. e.V.
 * @link           http://www.websitebaker2.org/
 * @license        http://www.gnu.org/licenses/gpl.html
 * @platform       WebsiteBaker 2.8.3
 * @requirements   PHP 5.2.2 and higher
 * @version        $Id: CKEditorPlus.php 137 2012-03-17 23:29:07Z Luisehahne $
 * @filesource     $HeadURL: http://webdesign:8080/svn/ckeditor-dev/branches/ckeditor/CKEditorPlus.php $
 * @lastmodified   $Date: 2012-03-18 00:29:07 +0100 (So, 18. Mrz 2012) $
 *
 */

/**
var trident = !!navigator.userAgent.match(/Trident\/7.0/);
var net = !!navigator.userAgent.match(/.NET4.0E/);
var IE11 = trident && net
var IEold = ( navigator.userAgent.match(/MSIE/i) ? true : false );
if(IE11 || IEold){
alert("IE")
}else{
alert("Other")
}

const string ie = @"(?:\b(MS)?IE\s+|\bTrident\/7\.0;.*\s+rv:)(\d+)";
const string cm = @"\bMSIE\s+7\.0;.*\bTrident\/(\d+)\.0";

var request = HttpContext.Current.Request;
var ieMatch = new Regex(ie).Match(request.UserAgent);
var result = "N/A";

if (ieMatch.Success)
{
    var msieVersion = ieMatch.Groups[2].Value;
    result = "IE " + msieVersion;

    var cmMatch = new Regex(cm).Match(request.UserAgent);

    if (cmMatch.Success)
    {
        var cv = cmMatch.Groups[1].Value;

        if (!string.IsNullOrEmpty(cv))
        {
            var i = Convert.ToInt64(cv);
            var cmCalc = i < 8 && i > 4 ? i += 4 : 0;
            result = "IE " + 
                     cmCalc.ToString(CultureInfo.InvariantCulture) + " CV";
        }

}
Debug.WriteLine(result); 
 
 */


class CKEditorPlus extends CKEditor
{
    /**
     *    @var    boolean
     *
     */
    public $pretty = true;

    /**
     *    @var    array
     *
     */
    private $lookup_html = array(
        '&gt;'    => ">",
        '&lt;'    => "<",
        '&quot;' => "\"",
        '&amp;'     => "&"
    );

    /**
     *    Public var to force the editor to use the given params for width and height
     *
     */
    public $force = false;

    /**
     *
     *
     */
    public $paths = Array(
        'contentsCss' => "",
        'stylesSet' => "",
        'templates_files' => "",
        'customConfig' => ""
    );

    /**
     *
     *
     */
    public $aTypeFiles = array(
        'contentsCss' => Array(
            'editor.css',
            'css/editor.css',
            'editor/editor.css'
        ),
        'stylesSet' => Array(
            'editor.styles.js',
            'js/editor.styles.js',
            'editor/editor.styles.js'
        ),
        'templates_files' => Array(
            'editor.templates.js',
            'js/editor.templates.js',
            'editor/editor.templates.js'
        ),
        'customConfig' => Array(
            'wb_ckconfig.js',
            'js/wb_ckconfig.js',
            'editor/wb_ckconfig.js'
        )
    );

    private $templateFolder = '';


    public function setTemplatePath ($templateFolder='') {
       return;
 /**
 * ------------------------------------------------------------------------
 * old code deprecated
 */
       if($templateFolder=='') { return; }
        $this->templateFolder = $templateFolder;
        foreach($this->files as $key=>$val) {
            foreach($val as $temp_path) {
                $base = "/templates/".$this->templateFolder.$temp_path;
                if (true == file_exists(WB_PATH.$base) ){
                    $this->paths[$key] = (($key=="stylesSet") ? "wb:" : "").WB_URL.$base;
                    break;
                }
            }
        }

    }

/**
 *    JavaScript handels LF/LB in another way as PHP, even inside an array.
 *    So we're in the need of pre-parse the entries.
 *
 */
    public function javascript_clean_str( &$aStr) {
        $vars = array(
            '"' => "\\\"",
            '\'' => "",
            "\n" => "<br />",
            "\r" => ""
        );

        return str_replace( array_keys($vars), array_values($vars), $aStr);
    }

    /**
     *    @param    string    Any HTML-Source, pass by reference
     *
     */
    public function reverse_htmlentities(&$html_source) {

        $html_source = str_replace(
            array_keys( $this->lookup_html ),
            array_values( $this->lookup_html ),
            $html_source
        );
    }

    /**    *************************************
     *    Additional test for the wysiwyg-admin
     */

    /**
     *    @var    boolean
     *
     */
    public $wysiwyg_admin_exists = false;

    /**
     *    Public function to look for the wysiwyg-admin table in the used database
     *
     *    @param    object    Any DB-Connector instance. Must be able to use a "query" method inside.
     *
     */
    public function looking_for_wysiwyg_admin( &$db ) {
          $this->wysiwyg_admin_exists = false;
//          if ( ($result = $db->doQuery("SHOW TABLES")) ) {
//              while(false !== ($data = $result->fetchRow( MYSQL_NUM ) ) ) {
//                  if (TABLE_PREFIX."mod_editor_admin" == $data[0]) {
//                      $this->wysiwyg_admin_exists = true;
//                      break;
//                  }
//              }
//          }
    }

    /**
     *    Looks for an (local) url
     *
     *    @param    string    Key for tha assoc. config array
     *    @param    string    Local file we are looking for
     *    @param    string    Optional file-default-path if it not exists
     *
     */
    public function resolve_path( $Type = "", $sTargetPath, $sPathDefault ) {
      $sTargetPath = ltrim($sTargetPath,'/');
      $sPathDefault = ltrim($sPathDefault,'/');
        $sAbsUrl  =  $this->oReg->AppUrl;
        $sAbsPath =  $this->oReg->AppPath;
      $bTargetFound = false;

      foreach($this->aTypeFiles[$Type] as $sTargetRel) {
          if ( is_readable($sAbsPath.$sTargetPath.$sTargetRel) ) {
              $this->config[$Type] = $sAbsUrl.$sTargetPath.$sTargetRel;
              $bTargetFound = true;
          } else {
              if( $bTargetFound == false ) {
                  $this->config[$Type] = $sAbsUrl.$sPathDefault;
              }
          }
      }
      return;
/**
 * ------------------------------------------------------------------------
 * old code deprecated
 */
        if (true === file_exists($sAbsPath)) {
            $sPath = $this->oReg->AppUrl.$sPath;
        } else {
            $sPath = $this->oReg->AppUrl.$sPathDefault;
        }

        if (array_key_exists($Type, $this->paths)) {
            $this->config[$Type] = (($this->paths[$Type ] == "") ? $sPath : $this->paths[$Type] ) ;
        } else {
            $this->config[$Type] = $sPath;
        }
    }

    /**
     *    More or less for debugging
     *
     *    @param    string    Name
     *    @param    string    Any content. Pass by reference!
     *    @return    string    The "editor"-JS HTML code
     *
     */
    public function to_HTML( $name, &$content ) {

 //       $old_return = $this->returnOutput;
 //       $this->returnOutput = true;
        $temp_HTML= $this->editor( $name, $content  );
//        $this->returnOutput = $old_return;

        if (true === $this->pretty) {
            $temp_HTML = str_replace (", ", ",\n ", $temp_HTML);
            $temp_HTML = "\n\n\n".$temp_HTML."\n\n\n";
        }

        return $temp_HTML;
    }
}
