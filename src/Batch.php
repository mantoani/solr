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

class Batch extends Connection{

  var $url;

	function __construct($url){
		$this->url = $url;
	}

	function toArray(){
		return array($this);
	}

  function index($docs, $path){
    $content = json_encode($docs);
		if(!file_exists($path)){
			mkdir($path, 0755, true);
		}
		$path = $path."/batch.json";
		$fopen = fopen($path, 'w+');
    $success = fwrite($fopen, $content);
		fclose($fopen);

		if($success){
			$exec = 
			'curl -X -POST \''.$this->url.'/update/?commit=true\' -H \'Content-type:application/json\' --data-binary @'.$path;
      
			$response = shell_exec($exec);

			return json_decode($response);
		}

		return false;
  }


}

?>
