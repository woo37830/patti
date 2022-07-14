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
//	private $data;

	/**
	 * EmailExtract
	 *
	 * @param mixed $inFile
	 * @access public
	 * @return void
	 */
	public function __construct($data)
	{
//		this.data = $data;
//		$this->extractEmailFromText($data);
		$this->writeList($this->extractEmailFromText($data));
//		$this->setinputFile($inFile);
//		$this->writeList($this->process());
	}

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

	protected function extractEmailFromText($data)
	{
		$emailList = array();
		$pattern = '/([a-zA-Z0-9+._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z._-]{2,10})/';
		preg_match_all($pattern, $data, $matches);
		if(count($matches) != 0)
		{
			foreach($matches[1] as $item)
			{
				array_push($emailList, $item);
			}
		}
		return array_unique($emailList);
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
}
?>
