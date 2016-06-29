<?php

require_once(dirname(__FILE__) . '/../includes/phpmailer-5.2.9/class.phpmailer.php');
require_once(dirname(__FILE__) . '/../includes/phpmailer-5.2.9/class.smtp.php');
/**
 * HtmlEmail - a clean wrapper on the phpmailer product (http://phpmailer.sourceforge.net)
 *
 * @package PegasusPHP
 */
class HtmlEmail
{
    protected $_phpmailer = null;
    protected $_toEmailList = array();
    protected $_ccEmailList = array();
    protected $_bccEmailList = array();

    public function __construct()
    {
        $this->_phpmailer = new PHPMailer();

        // English Language setup to fix bad HTML format
        $this->_phpmailer->SetLanguage("en", dirname(__FILE__) . '/../includes/phpmailer-5.2.9/language/');

        // If you use 8bit encoding you have to pay attention to the line length or you will
        // get random ! chars every 1000 characters in any HTML emails sent.  Switching to base64
        // prevent this inclusion
        $this->_phpmailer->Encoding = "quoted-printable";
    }

    /**
     * Set the from name and address
     *
     * @param string $email the "from" email address
     * @param string $name the "from" email name
     */
    public function from($email, $name = "")
    {

        $emailAddress = $email;
        $emailAddressList = EmailUtil::explodeAddresses($email);

        if (count($emailAddressList) > 0) {
            $emailAddress = $emailAddressList[0];
        }

        if ($name == "") {
            $name = $emailAddress;
        }

        $this->_phpmailer->From = $emailAddress; // From Email
        $this->_phpmailer->FromName = $name; // From Name
        $this->_phpmailer->Sender = $emailAddress; // Return Path
    }

    /**
     * Set the to name and address
     * @param string $email the address to send the email to
     * @param string $name the name of the recipient
     */
    public function to($email, $name = '')
    {
        $emailAddressList = EmailUtil::explodeAddresses($email);

        if ($name == '') {
            $name = $email;
        }

        foreach ($emailAddressList as $emailAddress) {
            $this->_toEmailList[] = $emailAddress;
            $this->_phpmailer->AddAddress($emailAddress, $name);
        }
    }

    /**
     * Set the return path of this mail
     * @param $email the address of the return path
     */
    public function setReturnPath($email)
    {
        $this->_phpmailer->Sender = $email;
    }

    /**
     * Add an email address to CC the email to
     * @param $email the address to cc
     */
    public function cc($email, $name = '')
    {
        foreach (EmailUtil::explodeAddresses($email) as $emailAddress) {
            $this->_ccEmailList[] = $emailAddress;
            $this->_phpmailer->addCC($email,$name);
        }
    }

    /**
     * Add an custom header to the email
     * @param $header the custom header value
     */
    public function addCustomHeader($header)
    {
        $this->_phpmailer->AddCustomHeader($header);
    }

    /**
     * Add an email address to BCC the email to
     * @param $email the address to bcc
     */
    public function bcc($email,$name='')
    {
        foreach (EmailUtil::explodeAddresses($email) as $emailAddress) {
            $this->_bccEmailList[] = $emailAddress;
            $this->_phpmailer->addBCC($email,$name);
        }
    }

    /**
     * The subject of the email
     * @param $subject the subject of the email
     */
    public function subject($subject)
    {
        $this->_phpmailer->Subject = $subject;
    }

    /**
     * Attach a local file to the email
     * @param string $path the path of the file to attach
     * @param string $name the name of the file to attach
     * @param string $encoding the type of encoding to encode the file with (base64 is default)
     * @param string $type the mime type of the attachment
     */
    public function attach($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
    {
        return $this->_phpmailer->AddAttachment($path, $name, $encoding, $type);
    }

    /**
     * Set the message/html of the email
     * @param $message string the html email body
     */
    public function message($message)
    {
        $this->_phpmailer->MsgHTML($message);
    }

    /**
     * Send the email to the recipients
     * @return bool true if the email was sent otherwise false
     */
    public function send()
    {
        return $this->_phpmailer->Send();
    }

    public function getError()
    {
        return $this->_phpmailer->ErrorInfo;
    }

    public function setEncoding($encoding) {
        $this->_phpmailer->Encoding = $encoding;
    }
}