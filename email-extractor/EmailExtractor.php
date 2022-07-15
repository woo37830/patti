<?php
/**
 * EmailExtractor
 *
 * Extract email addresses from an input text
 * @package
 * @version $id$
 * @author Istvan Dobrentei <http://dobrenteiistvan.hu>
 */
class EmailExtractor
{

	private $inputFile;
	private $regexEmail = '/([a-zA-Z0-9+._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z._-]{2,10})/';

	private $regexPhone = '/.*(\([2-9]\d{2}\)\s?\d{3}[.-]\d{4}).*/';
//	this matches (ddd) ddd-dddd

	/**
	 * process
	 *
	 * @access protected
	 * @return void
	 */
	protected function process()
	{
		$handle = fopen($this->inputFile, 'r');
		while (!feof($handle))
		{
			$data = fgets($handle, 1024);
			$emailList = extractEmailFromText( $data );
		}
		fclose($handle);
		return array_unique($emailList);
	}

  protected function extractPatternFromText( $pattern, $data)
	{
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
	public function extractEmailFromText($data)
	{
		return $this->extractPatternFromText($this->regexEmail, $data);
	}

	public function extractPhoneFromText($data)
	{
		$formatted = preg_replace($this->regexPhone, '$1', $data);

		return $formatted;
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
	 * setinputFile
	 *
	 * @param mixed $inFile
	 * @access protected
	 * @return void
	 */
	protected function setinputFile($inFile)
	{
		$this->inputFile = $inFile;
	}

	/**
	 * EmailExtract
	 *
	 * @param mixed $inFile
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
//		this.data = $data;
//		$this->extractEmailFromText($data);
//		$this->writeList($this->extractPatternFromText($this->regexPhone, $data));
//		$this->writeList($this->extractPatternFromText($this->regexEmail, $data));
//		$this->setinputFile($inFile);
//		$this->writeList($this->process());
	}
}
?>
