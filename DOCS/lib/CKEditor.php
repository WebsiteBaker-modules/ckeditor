<?php
/*
* Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
* For licensing, see LICENSE.html or http://ckeditor.com/license
*/
/**
 * \brief CKEditor class that can be used to create editor
 * instances in PHP pages on server side.
 * @see http://ckeditor.com
 *
 * Sample usage:
 * @code
 * $CKEditor = new CKEditor();
 * $CKEditor->editor("editor1", "<p>Initial value.</p>");
 * @endcode
 */
class CKEditor
{
  /**
   * The version of %CKEditor.
   */
  const version = '4.4.6';
  /**
   * A constant string unique for each release of %CKEditor.
   */
  const timestamp = 'B8DJ5M3';
  /**
   * URL to the %CKEditor installation directory (absolute or relative to document root).
   * If not set, CKEditor will try to guess it's path.
   *
   * Example usage:
   * @code
   * $CKEditor->basePath = '/ckeditor/';
   * @endcode
   */
  public $basePath;
  /**
   * An array that holds the global %CKEditor configuration.
   * For the list of available options, see http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
   *
   * Example usage:
   * @code
   * $CKEditor->config['height'] = 400;
   * // Use @@ at the beggining of a string to ouput it without surrounding quotes.
   * $CKEditor->config['width'] = '@@screen.width * 0.8';
   * @endcode
   */
  public $config = array();
  /**
   * A boolean variable indicating whether CKEditor has been initialized.
   * Set it to true only if you have already included
   * &lt;script&gt; tag loading ckeditor.js in your website.
   */
  public $initialized = false;
  /**
   * Boolean variable indicating whether created code should be printed out or returned by a function.
   *
   * Example 1: get the code creating %CKEditor instance and print it on a page with the "echo" function.
   * @code
   * $CKEditor = new CKEditor();
   * $CKEditor->returnOutput = true;
   * $code = $CKEditor->editor("editor1", "<p>Initial value.</p>");
   * echo "<p>Editor 1:</p>";
   * echo $code;
   * @endcode
   */
  public $returnOutput = false;
  /**
   * An array with textarea attributes.
   *
   * When %CKEditor is created with the editor() method, a HTML &lt;textarea&gt; element is created,
   * it will be displayed to anyone with JavaScript disabled or with incompatible browser.
   */
  public $textareaAttributes = array( "rows" => 8, "cols" => 60);
  /**
   * A string indicating the creation date of %CKEditor.
   * Do not change it unless you want to force browsers to not use previously cached version of %CKEditor.
   */
  public $timestamp = "B8DJ5M3";
  /**
   * An array that holds global event listeners.
   */
  private $globalEvents = array();
  /**
   * An array that holds event listeners.
   */
  private $events = array();
  /**
   * json_last_error — JSON error codes
   */
  private $aMessage = array(
    'JSON_ERROR_NONE',
    'JSON_ERROR_DEPTH',
    'JSON_ERROR_STATE_MISMATCH',
    'JSON_ERROR_CTRL_CHAR',
    'JSON_ERROR_SYNTAX',
    'JSON_ERROR_UTF8',
    );
  /**
   @var object instance of the WbAdaptor object */
  private $_oReg = null;
  /**
   @var object instance of the application object */
  private $_oApp = null;
  /**
   @var object instance of the database object */
  private $_oDb = null;
  /** Indents a flat JSON string to make it more human-readable. */
  public $prettyPrintJson = false;
  /**
   * CKE path, url, rel
   *
   * $aAddonParams['AddonDir']
   * $aAddonParams['AddonUrl']
   * $aAddonParams['AddonPath']
   * $aAddonParams['AddonAbsPath']
   * $aAddonParams['AddonRelPath']
   * $aAddonParams['ckeAbsPath']
   * $aAddonParams['ckeRelPath']
   * $aAddonParams['Template']
   * $aAddonParams['TemplateAbsPath']
   * $aAddonParams['TemplateRelPath']
   *
   */
  private $_aAddonParams = array();
  /**
   * Main Constructor.
   *
   *  @param $basePath (string) URL to the %CKEditor installation directory (optional).
   */
  public function __construct( array $AddonParams)
  {
    $this->_aAddonParams = $AddonParams;
    $this->getAddonIni();
  }
/*
    public function __construct($basePath = null) {
        if (!empty($basePath)) {
            $this->basePath = $basePath;

        }
    }
*/
  private function getAddonIni()
  {
    $this->oApp = ( isset( $GLOBALS['admin']) ? $GLOBALS['admin'] : $GLOBALS['wb']);
    $this->oDb = WbDatabase::getInstance();
    $this->oReg = WbAdaptor::getInstance();
    $this->basePath = ( $this->_aAddonParams['ckeRelPath']);
  }
  /**
   * CKEditor::setJsonEncode()
   * only works with UTF-8 encoded data.
   * in moment not in use was for test only
   *
   * @param mixed $obj Can be any type except a resource.
   * @param integer $iBitmask consisting of
   *                         PHP_JSON_HEX_TAG,
   *                         PHP_JSON_HEX_AMP,
   *                         PHP_JSON_HEX_APOS
   * @return string JSON representation of $obj
   *
   */
  public function setJsonEncode( $obj, $iBitmask = 0)
  {
    $iBitmask = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
    //        $retJson = ( (version_compare(PHP_VERSION, '5.3.0') < 0 ) ? json_encode($obj) : json_encode($obj, $iBitmask ) );
    return '"'.str_replace( array(
      "\\",
      "/",
      "\n",
      "\t",
      "\r",
      "\x08",
      "\x0c",
      '"'), array(
      '\\\\',
      '\\/',
      '\\n',
      '\\t',
      '\\r',
      '\\b',
      '\\f',
      '\"'), json_encode( $obj)).'"';
  }
  /**
   * Format a flat JSON string to make it more human-readable
   * original code: http://www.daveperrett.com/articles/2008/03/11/format-json-with-php/
   * adapted to allow native functionality in php version >= 5.4.0
   *
   * @param string $json The original JSON string to process
   *        When the input is not a string it is assumed the input is RAW
   *        and should be converted to JSON first of all.
   * @return string Indented version of the original JSON string
   *
   */
  public function getPrettyPrintJson( $json)
  {
    if( !is_string( $json)) {
      if( phpversion() && ( phpversion() >= 5.4) && $this->prettyPrintJson) {
        return json_encode( $json, JSON_PRETTY_PRINT);
      }
      $json = json_encode( $json);
    }
    if( $this->prettyPrintJson === false) {
      return $json;
    }
    $result = '';
    $pos = 0; // indentation level
    $strLen = strlen( $json);
    $indentStr = "\t";
    $newLine = "\n";
    $prevChar = '';
    $outOfQuotes = true;
    for ( $i = 0; $i < $strLen; $i++) {
      // Grab the next character in the string
      $char = substr( $json, $i, 1);
      // Are we inside a quoted string?
      if( $char == '"' && $prevChar != '\\') {
        $outOfQuotes = !$outOfQuotes;
      }
      // If this character is the end of an element,
      // output a new line and indent the next line
      else
        if( ( $char == '}' || $char == ']') && $outOfQuotes) {
          $result .= $newLine;
          $pos--;
          for ( $j = 0; $j < $pos; $j++) {
            $result .= $indentStr;
          }
        }
      // eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
        else
          if( $outOfQuotes && false !== strpos( " \t\r\n", $char)) {
            continue;
          }
      // Add the character to the result string
      $result .= $char;
      // always add a space after a field colon:
      if( $char == ':' && $outOfQuotes) {
        $result .= ' ';
      }
      // If the last character was the beginning of an element,
      // output a new line and indent the next line
      if( ( $char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
        $result .= $newLine;
        if( $char == '{' || $char == '[') {
          $pos++;
        }
        for ( $j = 0; $j < $pos; $j++) {
          $result .= $indentStr;
        }
      }
      $prevChar = $char;
    }
    return $result;
  }
  /**
   * Takes a JSON encoded string and converts it into a PHP variable
   * JSON::Decode()
   * @param mixed $json
   * @param bool $toAssoc
   * @return array
   */
  public function getJsonDecode( $json, $toAssoc = false)
  {
    $iError = 0;
    $retJson = json_decode( $json, $toAssoc);
    if( ( $iError = intval( json_last_error())) != 0) {
      throw new Exception( 'JSON Error: '.$this->aMessage[$iError]);
    }
    return $retJson;
  }
  /**
   * Creates a %CKEditor instance.
   * In incompatible browsers %CKEditor will downgrade to plain HTML &lt;textarea&gt; element.
   *
   * @param $name (string) Name of the %CKEditor instance (this will be also the "name" attribute of textarea element).
   * @param $value (string) Initial value (optional).
   * @param $config (array) The specific configurations to apply to this editor instance (optional).
   * @param $events (array) Event listeners for this editor instance (optional).
   *
   * Example usage:
   * @code
   * $CKEditor = new CKEditor();
   * $CKEditor->editor("field1", "<p>Initial value.</p>");
   * @endcode
   *
   * Advanced example:
   * @code
   * $CKEditor = new CKEditor();
   * $config = array();
   * $config['toolbar'] = array(
   *     array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
   *     array( 'Image', 'Link', 'Unlink', 'Anchor' )
   * );
   * $events['instanceReady'] = 'function (ev) {
   *     alert("Loaded: " + ev.editor.name);
   * }';
   * $CKEditor->editor("field1", "<p>Initial value.</p>", $config, $events);
   * @endcode
   */
  public function editor( $name, $value = "", $config = array(), $events = array())
  {
    $attr = "";
    foreach ( $this->textareaAttributes as $key => $val) {
      $attr .= " ".$key.'="'.str_replace( '"', '&quot;', $val).'"';
    }
    // add id to solve the metag name issues
    $out = "<textarea id=\"".$name."_m\" name=\"".$name."\"".$attr.">".htmlspecialchars( $value).
      "</textarea>\n";
    if( !$this->initialized) {
      $out .= $this->init();
    }
    $_config = $this->configSettings( $config, $events);
    $_Json = $this->jsEncode( $_config);
    //print '<pre style="text-align: left;"><strong>function '.__FUNCTION__.'( '.''.' );</strong>  basename: '.basename(__FILE__).'  line: '.__LINE__.' -> <br />';
    //print_r( $_Json ); print '</pre>'; // flush ();sleep(10); die();
    $js = $this->returnGlobalEvents();
    if( !empty( $_config)) $js .= "CKEDITOR.replace('".$name."_m', ".$_Json.");";
    else  $js .= "CKEDITOR.replace('".$name."_m');";
    $out .= $this->script( $js);
    if( !$this->returnOutput) {
      print $out;
      $out = "";
    }
    return $out;
  }
  /**
   * Replaces a &lt;textarea&gt; with a %CKEditor instance.
   *
   * @param $id (string) The id or name of textarea element.
   * @param $config (array) The specific configurations to apply to this editor instance (optional).
   * @param $events (array) Event listeners for this editor instance (optional).
   *
   * Example 1: adding %CKEditor to &lt;textarea name="article"&gt;&lt;/textarea&gt; element:
   * @code
   * $CKEditor = new CKEditor();
   * $CKEditor->replace("article");
   * @endcode
   */
  public function replace( $id, $config = array(), $events = array())
  {
    $out = "";
    if( !$this->initialized) {
      $out .= $this->init();
    }
    $_config = $this->configSettings( $config, $events);
    $js = $this->returnGlobalEvents();
    if( !empty( $_config)) {
      $js .= "CKEDITOR.replace('".$id."', ".$this->jsEncode( $_config).");";
    } else {
      $js .= "CKEDITOR.replace('".$id."');";
    }
    $out .= $this->script( $js);
    if( !$this->returnOutput) {
      print $out;
      $out = "";
    }
    return $out;
  }
  /**
   * Replace all &lt;textarea&gt; elements available in the document with editor instances.
   *
   * @param $className (string) If set, replace all textareas with class className in the page.
   *
   * Example 1: replace all &lt;textarea&gt; elements in the page.
   * @code
   * $CKEditor = new CKEditor();
   * $CKEditor->replaceAll();
   * @endcode
   *
   * Example 2: replace all &lt;textarea class="myClassName"&gt; elements in the page.
   * @code
   * $CKEditor = new CKEditor();
   * $CKEditor->replaceAll( 'myClassName' );
   * @endcode
   */
  public function replaceAll( $className = null)
  {
    $out = "";
    if( !$this->initialized) {
      $out .= $this->init();
    }
    $_config = $this->configSettings();
    $js = $this->returnGlobalEvents();
    if( empty( $_config)) {
      if( empty( $className)) {
        $js .= "CKEDITOR.replaceAll();";
      } else {
        $js .= "CKEDITOR.replaceAll('".$className."');";
      }
    } else {
      $classDetection = "";
      $js .= "CKEDITOR.replaceAll( function(textarea, config) {\n";
      if( !empty( $className)) {
        $js .= "  var classRegex = new RegExp('(?:^| )' + '".$className."' + '(?:$| )');\n";
        $js .= "  if (!classRegex.test(textarea.className))\n";
        $js .= "    return false;\n";
      }
      $js .= "	CKEDITOR.tools.extend(config, ".$this->jsEncode( $_config).", true);";
      $js .= "} );";
    }
    $out .= $this->script( $js);
    if( !$this->returnOutput) {
      print $out;
      $out = "";
    }
    return $out;
  }
  /**
   * Adds event listener.
   * Events are fired by %CKEditor in various situations.
   *
   * @param $event (string) Event name.
   * @param $javascriptCode (string) Javascript anonymous function or function name.
   *
   * Example usage:
   * @code
   * $CKEditor->addEventHandler('instanceReady', 'function (ev) {
   *     alert("Loaded: " + ev.editor.name);
   * }');
   * @endcode
   */
  public function addEventHandler( $event, $javascriptCode)
  {
    if( !isset( $this->events[$event])) {
      $this->events[$event] = array();
    }
    // Avoid duplicates.
    if( !in_array( $javascriptCode, $this->events[$event])) {
      $this->events[$event][] = $javascriptCode;
    }
  }
  /**
   * Clear registered event handlers.
   * Note: this function will have no effect on already created editor instances.
   *
   * @param $event (string) Event name, if not set all event handlers will be removed (optional).
   */
  public function clearEventHandlers( $event = null)
  {
    if( !empty( $event)) {
      $this->events[$event] = array();
    } else {
      $this->events = array();
    }
  }
  /**
   * Adds global event listener.
   *
   * @param $event (string) Event name.
   * @param $javascriptCode (string) Javascript anonymous function or function name.
   *
   * Example usage:
   * @code
   * $CKEditor->addGlobalEventHandler('dialogDefinition', 'function (ev) {
   *     alert("Loading dialog: " + ev.data.name);
   * }');
   * @endcode
   */
  public function addGlobalEventHandler( $event, $javascriptCode)
  {
    if( !isset( $this->globalEvents[$event])) {
      $this->globalEvents[$event] = array();
    }
    // Avoid duplicates.
    if( !in_array( $javascriptCode, $this->globalEvents[$event])) {
      $this->globalEvents[$event][] = $javascriptCode;
    }
  }
  /**
   * Clear registered global event handlers.
   * Note: this function will have no effect if the event handler has been already printed/returned.
   *
   * @param $event (string) Event name, if not set all event handlers will be removed (optional).
   */
  public function clearGlobalEventHandlers( $event = null)
  {
    if( !empty( $event)) {
      $this->globalEvents[$event] = array();
    } else {
      $this->globalEvents = array();
    }
  }
  /**
   * Prints javascript code.
   *
   * @param string $js
   */
  private function script( $js)
  {
    $out = "<script type=\"text/javascript\">";
    $out .= "//<![CDATA[\n";
    $out .= $js;
    $out .= "\n//]]>";
    $out .= "</script>\n";
    return $out;
  }
  /**
   * Returns the configuration array (global and instance specific settings are merged into one array).
   *
   * @param $config (array) The specific configurations to apply to editor instance.
   * @param $events (array) Event listeners for editor instance.
   */
  private function configSettings( $config = array(), $events = array())
  {
    $_config = $this->config;
    $_events = $this->events;
    if( is_array( $config) && !empty( $config)) {
      $_config = array_merge( $_config, $config);
    }
    if( is_array( $events) && !empty( $events)) {
      foreach ( $events as $eventName => $code) {
        if( !isset( $_events[$eventName])) {
          $_events[$eventName] = array();
        }
        if( !in_array( $code, $_events[$eventName])) {
          $_events[$eventName][] = $code;
        }
      }
    }
    if( !empty( $_events)) {
      foreach ( $_events as $eventName => $handlers) {
        if( empty( $handlers)) {
          continue;
        } else
          if( count( $handlers) == 1) {
            $_config['on'][$eventName] = '@@'.$handlers[0];
          } else {
            $_config['on'][$eventName] = '@@function (ev){';
            foreach ( $handlers as $handler => $code) {
              $_config['on'][$eventName] .= '('.$code.')(ev);';
            }
            $_config['on'][$eventName] .= '}';
          }
      }
    }
    return $_config;
  }
  /**
   * Return global event handlers.
   */
  private function returnGlobalEvents()
  {
    static $returnedEvents;
    $out = "";
    if( !isset( $returnedEvents)) {
      $returnedEvents = array();
    }
    if( !empty( $this->globalEvents)) {
      foreach ( $this->globalEvents as $eventName => $handlers) {
        foreach ( $handlers as $handler => $code) {
          if( !isset( $returnedEvents[$eventName])) {
            $returnedEvents[$eventName] = array();
          }
          // Return only new events
          if( !in_array( $code, $returnedEvents[$eventName])) {
            $out .= ( $code ? "\n" : "")."CKEDITOR.on('".$eventName."', $code);";
            $returnedEvents[$eventName][] = $code;
          }
        }
      }
    }
    return $out;
  }
  /**
   * Initializes CKEditor (executed only once).
   */
  private function init()
  {
    static $initComplete;
    $out = "";
    if( !empty( $initComplete)) {
      return "";
    }
    if( $this->initialized) {
      $initComplete = true;
      return "";
    }
    $args = "";
    $ckeditorPath = $this->ckeditorPath();
    if( !empty( $this->timestamp) && $this->timestamp != "%"."TIMESTAMP%") {
      $args = '?t='.$this->timestamp;
    }
    // Skip relative paths...
    if( strpos( $ckeditorPath, '..') !== 0) {
      $out .= $this->script( "window.CKEDITOR_BASEPATH='".$ckeditorPath."';");
    }
    $out .= "<script type=\"text/javascript\" src=\"".$ckeditorPath.'ckeditor.js'.$args."\"></script>\n";
    $extraCode = "";
    if( $this->timestamp != self::timestamp) {
      $extraCode .= ( $extraCode ? "\n" : "")."CKEDITOR.timestamp = '".$this->timestamp."';";
    }
    if( $extraCode) {
      $out .= $this->script( $extraCode);
    }
    $initComplete = $this->initialized = true;
    return $out;
  }
  /**
   * Return path to ckeditor.js.
   */
  private function ckeditorPath()
  {
    if( !empty( $this->basePath)) {
      return $this->basePath;
    }
    //		/**
    //		 * The absolute pathname of the currently executing script.
    //		 * Note: If a script is executed with the CLI, as a relative path, such as file.php or ../file.php,
    //		 * $_SERVER['SCRIPT_FILENAME'] will contain the relative path specified by the user.
    //		 */
    //		if (isset($_SERVER['SCRIPT_FILENAME'])) {
    //			$realPath = dirname($_SERVER['SCRIPT_FILENAME']);
    //		}
    //		else {
    //			/**
    //			 * realpath - Returns canonicalized absolute pathname
    //			 */
    //			$realPath = realpath( './' ) ;
    //		}
    //
    //		/**
    //		 * The filename of the currently executing script, relative to the document root.
    //		 * For instance, $_SERVER['PHP_SELF'] in a script at the address http://example.com/test.php/foo.bar
    //		 * would be /test.php/foo.bar.
    //		 */
    //		$selfPath = dirname($_SERVER['PHP_SELF']);
    //		$file = str_replace("\\", "/", __FILE__);
    //
    //		if (!$selfPath || !$realPath || !$file) {
    //			return "/ckeditor/";
    //		}
    //
    //		$documentRoot = substr($realPath, 0, strlen($realPath) - strlen($selfPath));
    //		$fileUrl = substr($file, strlen($documentRoot));
    //		$ckeditorUrl = str_replace("ckeditor_php5.php", "", $fileUrl);
    //
    return $this->_aAddonParams['ckeRelPath'];
  }
  /**
   * This little function provides a basic JSON support.
   *
   * @param mixed $val
   * @return string
   */
  private function jsEncode( $val)
  {
    return $this->getPrettyPrintJson( $val);
    if( is_null( $val)) {
      return 'null';
    }
    if( is_bool( $val)) {
      return $val ? 'true' : 'false';
    }
    if( is_int( $val)) {
      return $val;
    }
    if( is_float( $val)) {
      return str_replace( ',', '.', $val);
    }
    ;
    if( is_array( $val) || is_object( $val)) {
      if( is_array( $val) && ( array_keys( $val) === range( 0, count( $val) - 1))) {
        return '['.implode( ',', array_map( array( $this, 'jsEncode'), $val)).']';
      }
      $temp = array();
      foreach ( $val as $k => $v) {
        $temp[] = $this->jsEncode( "{$k}").':'.$this->jsEncode( $v);
      }
      return '{'.implode( ',', $temp).'}';
    }
    // String otherwise
    if( strpos( $val, '@@') === 0) {
      return substr( $val, 2);
    }
    if( strtoupper( substr( $val, 0, 9)) == 'CKEDITOR.') {
      return $val;
    }
    return '"'.str_replace( array(
      "\\",
      "/",
      "\n",
      "\t",
      "\r",
      "\x08",
      "\x0c",
      '"'), array(
      '\\\\',
      '\\/',
      '\\n',
      '\\t',
      '\\r',
      '\\b',
      '\\f',
      '\"'), $val).'"';
  }
}
