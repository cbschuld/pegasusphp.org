<?php 
/*
 * $Id: SDBPager.php,v 1.1 2012/07/18 12:56:10 smassey Exp $
 */

class SDBPager {
	private $_sdb = null;
	private $_rows_per_page;
	private $_page;
	private $_query = '';
	private $_select_params = array();
	private $_next_token = '';
	private $_total_records = 0;
	
	public function __construct($sdb=null, $query='', $selectparams=array(), $page=0, $perpage=10) {
		$this->setSDB($sdb);
		$this->setQuery($query);
		$this->addSelectParams($selectparams);
		$this->setPage($page);
		$this->setRowsPerPage($perpage);
	}

	public function process() {
		$sdb = $this->getSDB();
		
		// Calculate the total records
		$query = $this->getQuery();
		$query = str_replace('%1', 'count(*)', $query);
		$query = str_replace('%2', '', $query);
        $total = $sdb->select($query);
        $total = $this->convertSelectToArray($total);
        $total = $total[0]['Count'];
        $this->setTotalRecords((int)$total);

        // Retrieve the count needed to get the nextToken
        $jumpLimit = $this->getRowsPerPage() * ($this->getPage() - 1);
		$query = $this->getQuery();
		$query = str_replace('%1', 'count(*)', $query);        
        $query = str_replace('%2', ' LIMIT ' . $jumpLimit, $query);   
    
        $tmp = $sdb->select($query);
        $nextToken = ( isset($tmp->body->SelectResult->NextToken) && $tmp->body->SelectResult->NextToken != '' ) ? $tmp->body->SelectResult->NextToken : null;
        
	    // Perform the select to retrieve the data
	    $query = $this->getQuery();
	    $query = str_replace('%1', implode(',', $this->getSelectParams()), $query);
	    $query = str_replace('%2', ' LIMIT ' . $this->getRowsPerPage(), $query);
        if( $nextToken ) {          	      
            $data = $sdb->select($query, array('NextToken'=>$nextToken));
        }
        else {
            $data = $sdb->select($query);
        }

        return $this->convertSelectToArray($data);        
	}
	
	public function setSDB($v) { $this->_sdb = $v; }
	public function getSDB() { return $this->_sdb; }
	public function setRowsPerPage($v) { $this->_rows_per_page = $v; }
	public function getRowsPerPage() { return $this->_rows_per_page; }
	public function setPage($v) { $this->_page = $v; }
	public function getPage() { return $this->_page; }
	public function setNextToken($v) { $this->_next_token = $v; }
	public function getNextToken() { return $this->_next_token; }
	public function setQuery($v) { $this->_query = $v; }
	public function getQuery() { return $this->_query; }
	public function addSelectParams($v) { $this->_select_params = array_merge($this->_select_params, $v); }
	public function getSelectParam($k) { 
		if ( $key = array_search($k, $this->_select_params) ) {
			return $this->_select_params[$key];
		}
		else {
			return '';
		}
	}
	public function getSelectParams() { return $this->_select_params; }	
	public function setTotalRecords($v=0) { $this->_total_records = $v; }
	public function getTotalRecords() { return $this->_total_records; }
    public function convertSelectToArray($select) {
        $results = array();
        $x = 0;
        foreach($select->body->SelectResult->Item as $result) {
            foreach ($result as $field) {
                $results[$x][ (string) $field->Name ] = (string)
                $field->Value;
            }
            $x++;
        }
        return $results;
    }	
}


?>