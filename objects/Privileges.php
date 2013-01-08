<?php

	/**
	 * Privilege
	 *
	 * @package PegasusPHP
	 */

	if( ! defined('CHILDRENLIST_SPECIFIER') )	{ define( 'CHILDRENLIST_SPECIFIER',	'_CHILDREN_LIST'	); }
	if( ! defined('PARENTLIST_SPECIFIER') )		{ define( 'PARENTLIST_SPECIFIER',	'_PARENT_LIST'		); }
	if( ! defined('JAVASCRIPT_ID') )			{ define( 'JAVASCRIPT_ID',			'priv_'				); }
	if( ! defined('BASE_PRIVILEGE_COLOR') )		{ define( 'BASE_PRIVILEGE_COLOR',	'0xe0e0e0'			); }

	include_object('PrivilegePropel');
	include_object('UserPrivilegePropel');

	class Privilege {

		protected $_id;
		protected $_name;
		protected $_title;
		protected $_description;
		protected $_iPreReq;
		protected $_bVal;

		public function getId() 			{ return( $this->_id );				}
		public function getName() 			{ return( $this->_name );			}
		public function getTitle()			{ return( $this->_title );			}
		public function getDescription()	{ return( $this->_description );	}
		public function getPreReq() 		{ return( $this->_iPreReq );			}

		public function setID($id)				{ $this->_id = (int)$id; 					}
		public function setName($name)			{ $this->_name = (string)$name;			}
		public function setTitle($title)		{ $this->_title = (string)$title;		}
		public function setDescription($desc)	{ $this->_description = (string)$desc;	}
		public function setPreReq($req)			{ $this->_iPreReq = (int)$req;				}

		public function getValue() {
			return( (bool)$this->_bVal );
		}

		public function setValue($bvalue) {
			$this->_bVal = (bool)$bvalue;
		}
	}	
	
	
	
	
	class Privileges {
		private $_aPrivilege;
		private $_aPrivilegeLookup;
		private $_bHasRoot;
		
		public function __construct() {
			$this->_bHasRoot				= false;
			$this->_aPrivilege				= array();
			$this->_aPrivilegeLookup		= array();
			$this->loadPrivilegeList();
		}

		public function getPrivileges() { return $this->_aPrivilege; }

		private function deletePrivileges($iUserID) {
			foreach( UserPrivilegePropelQuery::create()->filterByUserId($iUserID)->find() as $pr ) {
				$pr->delete();
			}
			/*
			$c = new Criteria();
			$c->add(UserPrivilegePropelPeer::USERID, $iUserID, Criteria::EQUAL);
			$c->addAscendingOrderByColumn(UserPrivilegePropelPeer::PRIVILEGEID);
			UserPrivilegePropelPeer::doDelete($c);
			*/
		}
		
		public function save($iUserID) {

			$this->deletePrivileges($iUserID);

			foreach( $this->_aPrivilege as $key => $priv ) {
				if( $priv->getValue() ) {
					$userPrivilege = new UserPrivilegePropel();
					$userPrivilege->setUserId($iUserID);
					$userPrivilege->setPrivilegeId($priv->getId());
					$userPrivilege->save();
				}
			}
		}
		
		public function loadFromRequest() {
			foreach( $this->_aPrivilege as $key => $priv ) {
				$strPrivName = constant('JAVASCRIPT_ID') . strtolower($priv->getName()); 
				$this->setPrivilegeValue( $priv->getId(), ( Request::get($strPrivName) != '' ) );
			}
		}
		
		public function setRootUser($bHasRoot=true) {
			$this->_bHasRoot = $bHasRoot;
		}
		
		public function addPrivilege($priv) {
			$this->_aPrivilege[$priv->getId()] = $priv;
			$this->_aPrivilegeLookup[$priv->getName()] = $priv->getId();
		}
		
		public function &getPrivilege($strName) {
			$privRetVal = new Privilege();
			if( isset($this->_aPrivilegeLookup[$strName]) ) {
				$privRetVal = $this->_aPrivilege[ (int)$this->_aPrivilegeLookup[$strName] ];
			}
			return $privRetVal;
		}

		public function setPrivilegeValue($privID,$bValue) {
			$this->_aPrivilege[$privID]->setValue((bool)$bValue);
		}
		
		public function hasPrivilege($strPrivilege) {
			$bRetVal = false;

			if( $this->_bHasRoot ) {
				$bRetVal = true;
			}
			else if( isset( $this->_aPrivilegeLookup[$strPrivilege]) ) {
				$bRetVal = $this->getPrivilegeWorker( $this->_aPrivilegeLookup[$strPrivilege] ); 
			}
			else {
				// privilege is likely not loaded or we have a new privilege to load so we request a load
				Pegasus::log("Privilege::hasPrivilege()","Calling for Reload on a request for '{$strPrivilege}'","",true);
			}
			return( $bRetVal );
		}
		
		private function getPrivilegeWorker($id) {
			$bRetVal = false;
			if( $this->_aPrivilege[$id]->getValue() ) {
				if( $this->_aPrivilege[$id]->getPreReq() == 0 ) {
					$bRetVal = true;
				} else {
					$bRetVal = $this->getPrivilegeWorker( $this->_aPrivilege[$id]->getPreReq() );
				}
			}
			return( $bRetVal );
		}


		private function displayPrivilegeTreeWorker(&$oParentUserPrivileges,$id,$iLevel,&$aParent,&$aChildren,$bReadOnly) {
			$xhtml = '';
			$bGroup = false;
			foreach( $this->_aPrivilege as $key => $value ) {
				
				if( $value->getPreReq() == $id && $oParentUserPrivileges->hasPrivilege($value->getName()) ) {
					
					// Store Parent Name
					array_push( $aParent, $value->getName() );
					
					$aReturnedChildren = array();
					
					$xhtml = $this->displayPrivilegeTreeWorker( $oParentUserPrivileges, $value->getId(), $iLevel+1, $aParent, $aReturnedChildren, $bReadOnly) . $xhtml;
					
					$aChildren = array_merge( $aChildren, $aReturnedChildren );
					
					array_pop( $aParent );
					
					View::assign('color', (string)dechex( constant('BASE_PRIVILEGE_COLOR') + ($iLevel * 0x060606) ) );
					View::assign('privilegeid', strtolower(constant('JAVASCRIPT_ID') . $value->getName()));
					View::assign('privilegename', strtolower($value->getName()));
					View::assign('privilegetitle', $value->getTitle());
					View::assign('privilegevalue', $value->getValue());
					View::assign('privilegedescription', $value->getDescription());
					View::assign('readonly', $bReadOnly);

					View::assign('parentlist', $aParent);
					View::assign('childlist', $aReturnedChildren);

					View::assign('privilege_children_list', $value->getName() . constant('CHILDRENLIST_SPECIFIER'));
					View::assign('privilege_parent_list', $value->getName() . constant('PARENTLIST_SPECIFIER'));

					$xhtml = View::fetch('pegasus:privileges/display.tpl') . $xhtml;

					$strChildrenList = '';
					foreach( $aReturnedChildren as $item ) {
						$strChildrenList .=	( strlen($strChildrenList) > 0 ? ',' : '' ) .
											"'" .
											strtolower( constant('JAVASCRIPT_ID') . $item ) . 
											"'";
					} 

					$strParentsList = '';
					foreach( $aParent as $item ) {
						$strParentsList .=	( strlen($strParentsList) > 0 ? ',' : '' ) .
											"'" .
											constant('JAVASCRIPT_ID') . 
											strtolower($item) .
											"'";
					} 

					$xhtml = str_replace($value->getName() . constant('PARENTLIST_SPECIFIER'), $strParentsList, $xhtml );
					$xhtml = str_replace($value->getName() . constant('CHILDRENLIST_SPECIFIER'), $strChildrenList, $xhtml );

					array_push( $aChildren, $value->getName() );

					$bGroup = true;
				}
			}
			$strRetVal = $xhtml;
			
			if( $bGroup ) {
				View::assign('privileges',$xhtml);
				$strRetVal = View::fetch('pegasus:privileges/group.tpl');
			}
			return( $strRetVal );
		}

		public function getPrivilegesDisplay(&$oParentUserPrivileges,$bReadOnly=false) {
			Pegasus::addJavaScript("/pegasus/scripts/uiprivileges.js");
			$aParents = array();
			$aChildren = array();
			return( $this->displayPrivilegeTreeWorker($oParentUserPrivileges,0,1, $aParents, $aChildren, $bReadOnly ) );
		}
		
		public function loadPrivilegeList() {
			$c = new Criteria();
			$c->addAscendingOrderByColumn(PrivilegePropelPeer::ID);
			$privileges = PrivilegePropelPeer::doSelect($c);

			foreach( $privileges as $privilege ) {

				$priv = new Privilege();

				$priv->setID($privilege->getId());
				$priv->setName($privilege->getName());
				$priv->setTitle($privilege->getTitle());
				$priv->setDescription($privilege->getDescription());
				$priv->setPreReq($privilege->getPreReq());
				$priv->setValue(false);
				
				$this->addPrivilege($priv);
				// TODO: is this necessary?
				$priv = null; // delete();
			}
		}
		
		public function loadPrivileges($iUserID) {
			
			$c = new Criteria();
			$c->add(UserPrivilegePropelPeer::USERID, $iUserID, Criteria::EQUAL);
			$c->addAscendingOrderByColumn(UserPrivilegePropelPeer::PRIVILEGEID);
			$privileges = UserPrivilegePropelPeer::doSelect($c);

			foreach( $privileges as $privilege ) {
				$this->setPrivilegeValue($privilege->getPrivilegeId(),true);
			}
		}
	}
?>