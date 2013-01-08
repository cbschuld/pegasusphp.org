<?php 
/*
 * $Id: SDBPager.php,v 1.1 2012/07/02 12:56:10 smassey Exp $
 */

class SDBPager {
	private $_sdb;
	private $_recordCount;
	private $_pages;
	private $_page;
	private $_start = 0;
	private $_max = 0;
	
	public function __construct($sdb = null, $page = 1, $perpage = 25) {
		$this->setSDB($sdb);
		$this->setPage($page);
		$this->setRowsPerPage($perpage);
	}
	
	public function setSDB($v) {
		$this->_sdb = $v;
	}
	public function getSDB() {
		return $this->_sdb;
	}
	public function setRecordCount($v) {
		$this->_recordCount = $v;
	}
	public function getRecordCount() {
		return $this->_recordCount;
	}
	public function setPages($v) {
		$this->_pages = $v;
	}
	public function getPages() {
		return $this->_pages;
	}
	public function setPage($v) {
		$this->_page = $v;
	}
	public function getPage() {
		return $this->_page;
	}
	public function setStart($v) {
		$this->_start = $v;
	}
	public function getStart() {
		return $this->_start;
	}
	public function setMax($v) {
		$this->_max = $v;
	}	
	public function getMax() {
		return $this->_max;
	}
	public function setRowsPerPage($v) {
		$this->setMax($v);
		$this->figureStart();
	}
	public function figureStart() {
		$this->_start = ( ($this->_page - 1) * $this->_max );
	}
	public function getTotalRecordCount() {
		// WRITE ME
	}
	public function getTotalPages() {
		
	}
	public function getLastPage() {
		$pages = $this->getTotalPages();
		if ( $pages == 0 ) {
			return 1;
		}
		else {
			return $pages;
		}
	}
	public function atLastPage() {
		return $this->getPage() == $this->getLastPage();
	}
	public function getFirstPage() {
		return 1;
	}
	public function atFirstPage() {
		return $this->getPage() == $this->getFirstPage();
	}
	
	public function go() {
		
	}
}


?>