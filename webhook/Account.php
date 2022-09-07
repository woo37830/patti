<?php

//require_once '../utilities.php';
require 'Associate.php';
/**
 * Account
 *
 * Account is a record in AllClients under EngagemoreCRM that usually is created
 * when a signup is done in Thrivecart which includes a product-id.
 *
 * @package
 * @version $id$
 * @author John Wooten, Ph.D. <http://jwooten37830.com/blog>
 */
class Account extends Associate
{

  protected $accountid = 0;
  protected $inputStr = "";


  public static function read( $accountid )
  {
    $account = new Account($accountid);
    $account->errMessage = "Not yet implemented, contents are: $account";
    return $account;
  }

  public function write()
  {
      $this->errMessage = "Not yet implemented, contents are: $this";
      return;
  }

  /**
   * EmailExtract
   *
   * @param mixed $inFile
   * @access public
   * @return void
   */
  public function __construct($result_xml_string)
  {
    //  $this->acct = $account;
    if( $this->debug ) {
      echo "\n-----------constructor----------";
    }
      if ( isNullOrEmpty($result_xml_string) ) {
          $this->errMessage = "The result_xml_string is: ";
          return;
      } else {
      $this->inputStr = $result_xml_string;
    }

    }

  public function __toString()
  {
      return "\nName: " . $this->get_name() . "\nAddr: " . $this->get_addr() . "\nEmail: " . $this->email . "\nPhone: " . $this->phone .  "\nAcct: " . $this->accountid . "\n";
  }
}
?>
