<?php

require_once 'utilities.php';
require_once 'add_contact.php';
require_once 'get_contact.php';
require 'Associate.php';
/**
 * Contact
 *
 * Extract information about a Contact from a lead providers email
 * @package
 * @version $id$
 * @author John Wooten, Ph.D. <http://jwooten37830.com/blog>
 */
class Contact extends Associate
{

    protected $regexEmail = '/([a-zA-Z0-9+._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z._-]{2,10})/';

    protected $regexPhone = '/(\([2-9]\d{2}\)\s?\d{3}[.-]\d{4}|[2-9]\d{2}[.-]?\d{3}[.-]?\d{4})/';

    // this matches (ddd) ddd-dddd
    protected $inputStr;

    protected $contactid = -1;

    protected $data = "";

    protected $acct = "";

    protected $source = "";


    public static function read( $account, $val )
    {
      $contactid = getContact((int)$account, $val);
      $contact = new Contact(NULL);
      $contact->acct = (int)$account;
      $contact->contactid = $contactid;
      $contact->email = $val;
      if( $contactid == -1 )
      {
        $contact->errMessage = "Failure to read. Contents are: $contact";
      }
      return $contact;
    }

    public function write()
    {
      $contactid = addContactInstance($this);
      if( isNullOrEmpty($contactid) || $contactid == -1)
      {
        $this->errMessage = "Failure to write: $this";
      }
      else {
        echo "Contact: >$contactid<";
      }
      return $contactid;
    }

    public function get_source()
    {
        return $this->source;
    }

    public function set_source($val)
    {
        $this->source = $val;
    }

    public function get_acct()
    {
        return $this->acct;
    }

    public function get_acctNumber()
    {
      return 2607;
    }
    
    public function get_contactid()
    {
      return $this->contactid;
    }

    public function get_inputStr()
    {
        if ($this->acct != "") {
            $this->inputStr = "ACCOUNT=||" . $this->acct . "||\n" . $this->inputStr;
        }
        return $this->inputStr;
    }

    protected function extractAcctFromText($data)
    {
      if( ! isNullOrEmpty( $data ) )
      {
          if (strpos($data, "||") != 0) {
            if (preg_match('/\|\|(.*?)\|\|/', $data, $match) == 1) {
                $this->acct = $match[1];
                $data = str_replace("ACCOUNT=||", "", $data);
                $data = str_replace($this->acct, "", $data);
                $data = str_replace("||", "", $data);
                $this->inputStr = $data;
            }
        }
      }
    }

    protected function extractPatternFromText($pattern, $data)
    {
      if( isNullOrEmpty( $data ) )
      {
        return "";
      }
        $data = strip_tags($data);

        $list = array();
        preg_match_all($pattern, $data, $matches);
        if (count($matches) != 0) {
            foreach ($matches[1] as $item) {
                array_push($list, $item);
            }
        }
        return array_unique($list);
    }

    protected function extractEmailFromText($data)
    {
        return $this->extractPatternFromText($this->regexEmail, $data);
    }

    protected function extractPhoneFromText($data)
    {
        $temp = $this->extractPatternFromText($this->regexPhone, $data);
        if (count($temp) > 0) {
            return $temp[0];
        }
        return "";
    }

    protected function extractAddrFromText($input)
    {
        preg_match('/
		(\d++)    # Number (one or more digits) -> $matches[1]
		\s++      # Whitespace
		([^,]++), # Building + City (everything up until a comma) -> $matches[2]
		\s++      # Whitespace
		(\S++)    # "DC" part (anything but whitespace) -> $matches[3]
		\s++      # Whitespace
		(\d{5}(-\d{4})?)    # Number (one or more digits) -> $matches[4]
		/x', $input, $matches);
        return $matches;
    }

    protected function extractNameFromText($data)
    {
        $addr = $this->extractAddrFromText($data);
        $item = $addr[0];
        // print $item."\n";
        $data = preg_replace('/' . $item . '/', " ", $data);
        // print $data."\n";
        $email = $this->extractEmailFromText($data);
        $data = preg_replace('/' . $email[0] . '/', " ", $data);
        // print $data."\n";
        $name = preg_match('/([A-Z]\S++) ([A-Z]\S++)/', $data, $matches);
        return $matches[0];
    }

    public function set_inputStr($email_body)
    {
    //  echo "\nset_inputStr";
      if( ! isNullOrEmpty( $email_body ) )
      {

      $this->data = strip_tags($email_body);
      if ($this->debug) {
          print "\n....email_body......" . $this->data;
      }

      $this->extractAcctFromText($this->data);
      if ($this->debug) {
          print "\n....acctFromText......<$this->acct>  $this->data";
      }

      $temp = $this->extractEmailFromText($this->inputStr);
      if ( isset( $temp ) and $temp != "" and  count($temp) > 0) {
          $this->email = $temp[0];
          $this->data = str_replace($this->email, "", $this->data);
      }
      if ($this->debug) {
          print "\n...emailFromText.......<" . $this->email . ">" . $this->data;
      }

      $this->phone = $this->extractPhoneFromText($this->data);
      // print "\nphone: '".$this->phone."'";
      $this->data = str_replace($this->phone, "", $this->data);
      // print "\n..........<".$this->data.">\n";
      if ($this->debug) {
          print "\n....phoneFromText........<" . $this->phone . ">" . $this->data;
      }
      $temp = $this->extractAddrFromText($this->data);
      if (count($temp) > 0) {
          $this->addr = $temp[0];
      }
      // print $item."\n";
      $this->data = preg_replace('/' . $this->addr . '/', "", $this->data);
      if ($this->debug) {
          print "\n.....addressFromText.......<" . $this->addr . ">" . $this->data;
      }

      $temp = preg_match('/([A-Z]\S++) ([A-Z]\S++)/', $this->data, $matches);
      if (count($matches) > 0) {
          $this->name = $matches[0];
      }
      if ($this->debug) {
          print "\n.....nameFromText.......<" . $this->name . ">" . $this->data;
          print "\n";
      }
    }
    }

    /**
     * EmailExtract
     *
     * @param mixed $inFile
     * @access public
     * @return void
     */
    public function __construct($email_body)
    {
      //  $this->acct = $account;
      if( $this->debug ) {
        echo "\n-----------constructor----------";
      }
        if ( isNullOrEmpty($email_body) ) {
          //echo "\nNo argument passed";
            return;
        } else {
        $this->set_inputStr($email_body);
      }

      }


    public function __toString()
    {
        return "\nContactid: ".$this->contactid."\nName: " . $this->name . "\nAddr: " . $this->addr . "\nEmail: " . $this->email . "\nPhone: " . $this->phone . "\nSource: " . $this->source . "\nAcct: " . $this->acct . "\n";
    }
}
?>
