<?php
/**
* PHP AtD Interface
* (c) 2013 - Dennis Vink
* http://www.i3D.net
*/

class AtD {
	var $url;
	var $apikey;

	function __construct ($host = '127.0.0.1', $port = '1049', $ssl = false, $apikey = 'PHP-AtD') {
		$this->url = (($ssl) ? 'https://' : 'http://') . $host . ':' . $port . '/';
		$this->apikey = $apikey;
	}

	private function _xml2a (SimpleXMLElement $parent) {
		$array = array();
		foreach ($parent as $name => $element) {
			($node = & $array[$name])
				&& (1 === count($node) ? $node = array($node) : 1)
				&& $node = & $node[];

			$node = $element->count() ? $this->_xml2a($element) : trim($element);
		}
		return $array;
	}

	private function _atd ($query, $data) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url . $query);
		curl_setopt($ch, CURLOPT_POST, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=' . $this->apikey . '&data=' . urlencode($data));
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 90);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = new SimpleXMLElement(curl_exec($ch));
		return($this->_xml2a($response));
	}

	public function checkDocument($data) {
		return($this->_atd('checkDocument', $data));
	}

	public function checkGrammar($data) {
		return($this->_atd('checkGrammar', $data));
	}
}

/*
 ; Sample code:
 ; $atd = new AtD();
 ; print_r($atd->checkDocument('This iz a sample text wit, errorz to check.'));
 ; print_r($atd->checkGrammar('This iz a sample text wit, errorz to check.'));
*/

?>
