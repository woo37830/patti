<?php
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
class Account
{

  protected $errMessage = ""; // This is set if there is an error


  public function get_errMessage()
  {
    return $errMessage;
  }

  public function setDebug($value)
  {
      $this->debug = $value;
  }

  public static function read( $account, $val )
  {
    $contact = new Contact(NULL);
    $contact.set_acct( $account );
    $contact->errMessage = "Not yet implemented, contents are: $contact";
    return $contact;
  }

  public function write()
  {
      $this->errMessage = "Not yet implemented, contents are: $this";
      return;
  }

  /**
   * writeList
   *
   * @param mixed $list
   * @access protected
   * @return void
   */
  protected function writeList($list)
  {
      foreach ($list as $key => $value) {
          print $value . "\n";
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
      if ( $this->isNullOrEmpty($email_body) ) {
        //echo "\nNo argument passed";
          return;
      } else {
      $this->set_inputStr($email_body);
    }

    }

  public function get_info()
  {
      $last = exec('git log -1 --date=format:"%Y/%m/%d" --format="%ad"');
      $rev = exec('git rev-parse --short HEAD');
      $branch = exec('git rev-parse --abbrev-ref HEAD');

      return "\n---------------- $last ---- $rev ------ $branch --------\n";
  }

  public function __toString()
  {
      return "\nName: " . $this->name . "\nAddr: " . $this->addr . "\nEmail: " . $this->email . "\nPhone: " . $this->phone . "\nSource: " . $this->source . "\nAcct: " . $this->acct . "\n";
  }
}
?>
