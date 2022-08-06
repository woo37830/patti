<?php
/**
 * Prospect
 *
 * Extract information about a propsect from a lead providers email
 * @package
 * @version $id$
 * @author John Wooten, Ph.D. <http://jwooten37830.com/blog>
 */
class Prospect
{

	private $regexEmail = '/([a-zA-Z0-9+._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z._-]{2,10})/';

	private $regexPhone = '/(\([2-9]\d{2}\)\s?\d{3}[.-]\d{4}|[2-9]\d{2}[.-]?\d{3}[.-]?\d{4})/';
//	this matches (ddd) ddd-dddd

	private $inputStr;

	private $email = "";

	private $name = "";

	private $addr = "";

	private $phone = "";

	private $data = "";

	private $debug = false;

	private $for = "";

	public function get_for()
	{
		return $this->for;
	}

  public function get_name()
	{
		return $this->name;
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

	public function get_inputStr()
	{
		return $this->inputStr;
	}

	protected function setDebug($value)
	{
		$this->debug = $value;
	}
  private function extractPatternFromText( $pattern, $data)
	{
		$data = strip_tags($data);

		$list = array();
		preg_match_all($pattern, $data, $matches);
		if(count($matches) != 0)
		{
			foreach($matches[1] as $item)
			{
				array_push($list, $item);
			}
		}
		return array_unique($list);

	}
	private function extractEmailFromText($data)
	{
		return $this->extractPatternFromText($this->regexEmail, $data);
	}

	private function extractPhoneFromText($data)
	{
		$temp = $this->extractPatternFromText($this->regexPhone, $data);
		if( count($temp) > 0 )
		{
			return $temp[0];
		}
		return "";
	}

	private function extractAddrFromText($input)
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

private function extractNameFromText($data)
{
	$addr = $this->extractAddrFromText($data);
	$item = $addr[0];
//	print $item."\n";
	$data = preg_replace('/'.$item.'/'," ", $data);
//	print $data."\n";
	$email = $this->extractEmailFromText($data);
	$data = preg_replace('/'.$email[0].'/'," ",$data);
//	print $data."\n";
	$name = preg_match('/([A-Z]\S++) ([A-Z]\S++)/', $data, $matches);
	return $matches[0];
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
		foreach($list as $key=>$value)
		{
			print $value."\n";
		}
	}


	/**
	 * EmailExtract
	 *
	 * @param mixed $inFile
	 * @access public
	 * @return void
	 */

	public function __construct( $account, $email_body )
	{
		$this->for = $account;

		if ( $email_body === "" )
		{
			return;
		}
		$this->inputStr = $email_body;

		$this->data = strip_tags($email_body);
		if( $this->debug ) {
			print "\n..........".$this->data;
		}
		$temp = $this->extractEmailFromText($this->data);
		if( count($temp) > 0 )
		{
			$this->email = $temp[0];
			$this->data = str_replace($this->email, "", $this->data);
		}
		if( $this->debug ) {
			print "\n..........<".$this->email.">".$this->data;
		}

		$this->phone = $this->extractPhoneFromText($this->data);
//		print "\nphone: '".$this->phone."'";
		$this->data = str_replace($this->phone, "", $this->data);
//		print "\n..........<".$this->data.">\n";
		if( $this->debug ) {
			print "\n............<".$this->phone.">".$this->data;
		}
		$temp = $this->extractAddrFromText($this->data);
		if( count($temp) > 0 )
		{
			$this->addr = $temp[0];
		}
	//	print $item."\n";
		$this->data = preg_replace('/'.$this->addr.'/',"", $this->data);
		if( $this->debug) {
			print "\n............<".$this->addr.">".$this->data;
		}

		$temp = preg_match('/([A-Z]\S++) ([A-Z]\S++)/', $this->data, $matches);
		if( count($matches) > 0 )
		{
			$this->name = $matches[0];
		}
		if( $this->debug ) {
			print "\n............<".$this->name.">".$this->data;
			print "\n";
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
		return "Name: ".$this->name."\nAddr: ".$this->addr."\nProspect Email: ".$this->email."\nPhone: ".$this->phone."\nProspect for: ".$this->for."\n";
	}
}
?>
