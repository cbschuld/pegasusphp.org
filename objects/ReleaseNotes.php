<?php
/**
 * Class ReleaseNotes 
 * @package PegasusPHP
 */
class ReleaseNotes {

	private $_strTemplateFilename = '';
	private $_strXmlFilename = '';

	/**
	 * Default Constructor
	 */
	public function __construct() {}

	/**
	 * Default Destructor
	 */
	public function __destruct() {}

	public function setTemplateFilename($strFilename) {
		$this->_strTemplateFilename = $strFilename;
	}
	public function setXmlFilename($strFilename) {
		$this->_strXmlFilename = $strFilename;
	}

	public function getNotes() {
		
		$strRetVal = '';
		
		if( $this->_strTemplateFilename == '' ) {
			$this->_strTemplateFilename = dirname(__FILE__) . '/../templates/releasenotes.tpl';
		}

		if( $this->_strXmlFilename == '' ) {
			$this->_strXmlFilename = constant('BASE_PATH') . '/releasenotes.xml';
		}
		
		if( file_exists( $this->_strXmlFilename ) ) {
			$xml = simplexml_load_file($this->_strXmlFilename);		
			View::assign('releaseNotes',$xml);
			$strRetVal = View::fetch( $this->_strTemplateFilename );
		}
		return $strRetVal;
	}

};

?>