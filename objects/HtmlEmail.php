<?php
	require_once(dirname(__FILE__).'/../includes/phpmailer/class.phpmailer.php');
	/**
	 * HtmlEmail - a clean wrapper on the phpmailer product (http://phpmailer.sourceforge.net)
	 *
	 * @package PegasusPHP
	 */	
	class HtmlEmail {
		private $_phpmailer = null;
		public function __construct() {
			$this->_phpmailer = new PHPMailer();
			
			// English Language setup to fix bad HTML format
			$this->_phpmailer->SetLanguage("en", dirname(__FILE__).'/../includes/phpmailer/language/');

			// If you use 8bit encoding you have to pay attention to the line length or you will
			// get random ! chars every 1000 characters in any HTML emails sent.  Switching to base64
			// prevent this inclusion
			$this->_phpmailer->Encoding = "quoted-printable";
		}
		/**
		 * Set the from name and address
		 * @param $email the "from" email address
		 * @param $name the "from" email name
		 */
		public function from($email,$name="") {
			
			$emailAddress = $email;
			$emailAddressList = EmailUtil::explodeAddresses($email);
			
			if( count($emailAddressList) > 0 ) {
				$emailAddress = $emailAddressList[0]; 
			}
			
			if( $name == "" ) { $name = $emailAddress; }
			
			$this->_phpmailer->From = $emailAddress;    // From Email
			$this->_phpmailer->FromName = $name;        // From Name
			$this->_phpmailer->Sender = $emailAddress;  // Return Path
		}
		/**
		 * Set the to name and address
		 * @param $email the address to send the email to
		 * @param $name the name of the recipient
		 */
		public function to($email,$name='') {
			$emailAddress = $email;
			$emailAddressList = EmailUtil::explodeAddresses($email);
			
			if($name=='') { $name = $email; }
			
			foreach( $emailAddressList as $emailAddress ) {
				$this->_phpmailer->AddAddress($emailAddress,$name);
			}
		}
		/**
		 * Set the return path of this mail
		 * @param $email the address of the return path
		 */
		public function setReturnPath($email) {
			$this->_phpmailer->Sender = $email;
		}
		/**
		 * Add an email address to CC the email to
		 * @param $email the address to cc
		 */
		public function cc($email) {
			foreach( EmailUtil::explodeAddresses($email) as $emailAddress ) {
				$this->_phpmailer->AddCustomHeader('Cc: ' . $emailAddress);
			}
		}
		/**
		 * Add an custom header to the email 
		 * @param $header the custom header value
		 */
		public function addCustomHeader($header) {
			$this->_phpmailer->AddCustomHeader($header);
		}
		/**
		 * Add an email address to BCC the email to
		 * @param $email the address to bcc
		 */
		public function bcc($email) {
			foreach( EmailUtil::explodeAddresses($email) as $emailAddress ) {
				$this->_phpmailer->AddCustomHeader('Bcc: ' . $emailAddress);
			}
		}
		/**
		 * The subject of the email 
		 * @param $subject the subject of the email
		 */
		public function subject($subject) {
			$this->_phpmailer->Subject = $subject;
		}
		/**
		 * Attach a local file to the email
		 * @param $path the path of the file to attach
		 * @param $name the name of the file to attach
		 * @param $encoding the type of encoding to encode the file with (base64 is default)
		 * @param $type the mime type of the attachment
		 */
		public function attach($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream') {
			return $this->_phpmailer->AddAttachment($path,$name,$encoding,$type);
		}
		/**
		 * Set the message/html of the email
		 * @param $message the html email body
		 */
		public function message($message) { $this->_phpmailer->MsgHTML($message); }
		/**
		 * Send the email to the recipients
		 * @return bool true if the email was sent otherwise false
		 */
		public function send() { return $this->_phpmailer->Send(); }
		public function getError() { return $this->_phpmailer->ErrorInfo; }
	}
?>