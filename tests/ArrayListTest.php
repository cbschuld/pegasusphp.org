<?php
    class ArrayListTest extends PHPUnit_Framework_TestCase {
	public function testClear() {
	    $al = new ArrayList();
	    $al->add("a");
	    $al->add("b");
	    $al->add("c");
	    $al->clear();
	    $this->assertEquals($al->count(),0);
	}
	public function testCount() {
	    $al = new ArrayList();
	    $al->add("a");
	    $this->assertEquals($al->count(),1);
	    $al->add("b");
	    $al->add("c");
	    $this->assertEquals($al->count(),3);

	    $al2 = new ArrayList();
	    $this->assertEquals($al2->count(),0);
	}

	public function testAdd() {
	    $obj = new stdClass();
	    $obj->somevar = "test";

	    $al = new ArrayList();
	    $al->add($obj);

	    $this->assertEquals( $al->get(0), $obj);
	}

	public function testRemove() {
	    $obj1 = new stdClass();
	    $obj1->somevar = "test1";

	    $obj2 = new stdClass();
	    $obj2->somevar = "test2";

	    $obj3 = new stdClass();
	    $obj3->somevar = "test3";

	    $obj4 = new stdClass();
	    $obj4->somevar = "test4";

	    $al = new ArrayList();
	    $al->add($obj1);
	    $al->add($obj2);
	    $al->add($obj1);
	    $al->add($obj3);
	    $al->add($obj1);
	    $al->add($obj4);
	    $al->add($obj1);

	    $this->assertEquals( $al->count(), 7);

	    $al->remove($obj1);
	    $this->assertEquals( $al->count(), 3);
	}
	public function testRemove2() {
	    $obj1 = new stdClass();
	    $obj1->somevar = "test1";

	    $obj2 = new stdClass();
	    $obj2->somevar = "test2";

	    $obj3 = new stdClass();
	    $obj3->somevar = "test3";

	    $obj4 = new stdClass();
	    $obj4->somevar = "test4";

	    $al = new ArrayList();
	    $al->add($obj1);
	    $al->add($obj2);
	    $al->add($obj1);
	    $al->add($obj3);
	    $al->add($obj1);
	    $al->add($obj4);
	    $al->add($obj1);

	    $this->assertEquals( $al->count(), 7 );

	    $al->removeAt(2);
	    $this->assertEquals( $al->count(), 6 );
	    $this->assertEquals( $al->get(2), $obj3 );
	}
    }
?>