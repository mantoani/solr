<?php
/*!
 * Copyright (c) 2013 - Eric Mantoani <eu@eric.com.br>
 *
 * This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see [http://www.gnu.org/licenses/].
*/

namespace Solr;

use \stdClass;

class Connection{

	private $url;
	private $commitUrl = "/update/json?commit=true";
	private $selectUrl = "/select/?q=";

	function __construct(){}

	function setUrl($url){
		$this->url = $url;
	}

	function commitByCurl($doc){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url.$this->commitUrl);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);

		// proper USER-AGENT...
		curl_setopt($curl, CURL_HTTP_VERSION_1_1, true);
		curl_setopt($curl, CURLOPT_ENCODING, "gzip, deflate");
		curl_setopt($curl, CURL_HTTP_VERSION_1_1, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		// DO POST
		curl_setopt($curl,CURLOPT_POST, count($_POST));
		curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($doc));

		//Response and Status Error
		$curlSolrResult = new \stdClass();
		$curlSolrResult->response = curl_exec($curl);
    	$curlSolrResult->status   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		return $curlSolrResult;
	}

	function select($query){
		$request = new HttpRequest("get", $this->url.$this->selectUrl.$query);
		return (!$request->getError()) ? json_decode($request->getResponse()) : false;
	}

}

?>
