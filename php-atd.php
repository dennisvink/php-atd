<?php
/**
* PHP AtD Interface
* (c) 2013 - Dennis Vink
* http://www.i3D.net
*/

class AtD {
	var $url;
	var $apikey;

	function __construct ($host = '127.0.0.1', $port = '1049', $apikey = 'PHP-AtD', $ssl = false) {
		$this->url = (($ssl) ? 'https://' : 'http://') . $host . ':' . $port . '/';
		$this->apikey = $apikey;
	}

	private function _xml2a ($parent, $out = Array()) {
		foreach ( (array) $parent as $index => $node ) {
			$out[$index] = ( is_object ( $node ) ) ? $this->_xml2a ( $node ) : $node;
		}
		return $out;
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
 ; // Sample code:
 ; $atd = new AtD();	// Parameters are optional, by default it will look for an AtD server running locally.
 ;						// Optional parameters: AtD($host, $port, $apikey, $ssl)
 ;
 ; $result1 = $atd->checkDocument('This iz a sample text wit, errorz to check.');
 ; $result2 = $atd->checkGrammar('This iz a sample text wit, errorz to check.');
 ;
 ; print_r($result1);
 ; print_r($result2);
 ;
 */

?>
