<?php
    /**
     * ArrayList is a general collection class to speed up development
     *
     * @author cbschuld
     */
    class ArrayList {
	private $_coll = array();

	/**
	 * Adds an item to the end of the ArrayList.
	 * @param mixed $item adds this item to the end of the ArrayList
	 */
	public function add($item) { $this->_coll[] = $item; }
	/**
	 * Gets the element at the specified index.
	 * @param <type> $index the index to get
	 */
	public function get($index) {
	    if( isset($this->_coll[$index])) { return $this->_coll[$index]; }
	    return null;
	}
	/**
	 * Sets the element at the specified index.
	 * @return integer returns the number of items in the ArrayList
	 */
	public function set($index,$item) {
	    if( isset($this->_coll[$index])) { $this->_coll[$index] = $item; }
	    return $this->count();
	}
	/**
	 * Removes all occurrences of a specific item from the ArrayList.
	 * @param mixed $item removes this item from the ArrayList (all occurrences)
	 */
	public function remove($item) {
	    $_new_coll = array();
	    for($i = 0; $i < count($this->_coll); $i++ ) {
		if( $this->_coll[$i] != $item ) {
		    $_new_coll[] = $item;
		}
	    }
	    $this->_coll = $_new_coll;
	}
	/**
	 * Removes all elements from the ArrayList.
	 */
	public function clear() {
	    $this->_coll = array();
	}
	/**
	 * Removes the element at the specified index of the ArrayList.
	 * @param integer the index for which to remove the element from the ArrayList
	 */
	public function removeAt($index) {
	    $_new_coll = array();
	    for($i = 0; $i < count($this->_coll); $i++ ) {
		if( $i != $index ) {
		    $_new_coll[] = $this->_coll[$i];
		}
	    }
	    $this->_coll = $_new_coll;
	}
	/**
	 * Gets the number of elements actually contained in the ArrayList.
	 * @return integer the number of items in the collection
	 */
	public function count() {
	    return count($this->_coll);
	}
    }
?>
