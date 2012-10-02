<?php
	/**
	 * @package PegasusPHP-TEST
	 */
	class Address {
		public $line1;
		public $city;
		public $state;
		public $zip;
		public function Address($line1,$city,$state,$zip) {
			$this->line1 = $line1;
			$this->city  = $city;
			$this->state = $state;
			$this->zip   = $zip;
		}
	}
	
	$addresses = array (
	
		new Address('3065 South Colonial Street','Gilbert','Arizona','89295'),
		new Address('4575 Dean Martin Drive','Las Vegas','Nevada','89103'),
		
	);
	

	define('DISABLE_PROPEL',true);

	require_once(dirname(__FILE__).'/../pegasus.php');
	
	echo '<script type="text/javascript" src="/pegasus/scripts/jquery/jquery-1.2.3.min.js"></script>';

	$gm = new GoogleMap();
	$gm->setApiKey('ABQIAAAAaaTBuXbYmnv8K3EC1wYZhxSF-z-ynKvoyahDsdag1WIts_o_5hSoZG96sNAB6PcQY01TxkVbsz5eRw');
	
	$index = 0;
	
	foreach( $addresses as $address ) {
		
		$gm->setAddressLine($address->line1);
		$gm->setAddressCity($address->city);
		$gm->setAddressState($address->state);
		$gm->setAddressZip($address->zip);

		echo 'Verifying: '.$address->line1.', '.$address->city.', '.$address->state.' '.$address->zip.'<br/>';
		
		if( $gm->verifyAddress() ) {
			echo 'Verified - <strong>Ok</strong> ('.$gm->getStatus().') - <small>('.$gm->getMessage().') <a href="javascript:;" onclick="$(\'#response'.$index.'\').toggle();">View Reponse</a></small><br/>';
			echo '<small>Verified Address: '.$gm->getAddressLine().', '.$gm->getAddressCity().', '.$gm->getAddressState().' '.$gm->getAddressZip().'</small><br/>';
		}
		else {
			echo 'Verified - <strong>BAD</strong> ('.$gm->getStatus().') - <small>('.$gm->getMessage().') <a href="javascript:;" onclick="$(\'#response'.$index.'\').toggle();">View Reponse</a></small><br/>';
		}
		
		echo '<div id="response'.$index++.'" style="display:none;">';
		echo '<pre>'.$gm->getLastResponse().'</pre>';
		echo '</div>';
		
		echo '<hr>';
	}
?>