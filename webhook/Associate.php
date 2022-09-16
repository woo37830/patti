<?php
/**
 * Account
 *
 * Associate is the base class for any object with a name and address
 *
 * @package
 * @version $id$
 * @author John Wooten, Ph.D. <http://jwooten37830.com/blog>
 */

class Associate
{

  protected $name = "";

  public $errMessage = ""; // This is set if there is an error

  protected $email = "";

  protected $addr = "";

  protected $phone = "";

  protected $debug = FALSE;

  public function get_name()
  {
      return $this->name;
  }

  public function get_firstName()
  {
    if( $this->name == "" )
    {
      return "";
    }
    $array = explode(" ", $this->name);
    return $array[0];
  }

  public function get_lastName()
  {
    if( $this->name == "" )
    {
      return "";
    }
    $array = explode(" ", $this->name);
    if( sizeof($array) > 0 )
    {
      return $array[1];
    }
    return "";
  }

  public function get_addr()
  {
      return $this->addr;
  }

  public function get_phone()
  {
      return $this->phone;
  }

  public function get_email()
  {
      return $this->email;
  }

  public function get_errMessage()
  {
    return $this->errMessage;
  }

  public function setDebug($value)
  {
      $this->debug = $value;
  }

}
?>
