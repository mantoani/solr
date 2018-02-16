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

class Document extends Connection{

	function __construct($url){
		parent::setUrl($url);
	}

	function commit(){
		$commit = new \stdClass();
		$commit->add = $this->toArray();

		return parent::commitByCurl($commit);
	}

	function addField($name, $value){
		$this->$name = $value;
	}

	function toArray(){
		return array($this);
	}


	function fetchByDoc($doc){
		foreach($doc as $key => $value){
				if($key != "_version_"){
					$this->addField($key, $value);
				}
			}
	}

	function fetchBySolrId($solrId){
		$query = "solrId:$solrId&fl=*&wt=json&start=0&rows=1";
		$result = parent::select($query);
		if(count($result->response->docs) > 0){
			foreach($result->response->docs[0] as $key => $value){
				if($key != "_version_"){
					$this->addField($key, $value);
				}
			}
		}
	}

	//private (descomentar)
	function deleteByFieldValue($field, $value){
		//$this->addField($field, $value);
	}

	function deleteByQuery($query){
		$this->addField('query', $query);
		$commit = new \stdClass();
		$commit->delete = $this;

		return parent::commitByCurl($commit);

	}

	function deleteBySolrId($solrId){

		$this->addField('query', "solrId:".$solrId);

		$commit = new \stdClass();
		$commit->delete = $this;

		return parent::commitByCurl($commit);

	}


  function setSortDate(){
		$this->sortDate = "";

		if(isset($this->date)){
			$temp = explode("/", $this->date);
			$Y = (isset($temp[2])) ? $temp[2] : date("Y");
			$m = (isset($temp[1])) ? $temp[1] : date("m");
			$d = (isset($temp[0])) ? $temp[0] : date("d");
			$this->sortDate .= $Y."-".$m."-".$d."T";
		} else {
			$this->sortDate .= date("Y-m-d")."T";
		}

		if(isset($this->hour)){
			$this->sortDate .= $this->hour."Z";
		} else {
			$this->sortDate .= date("H:i:s")."Z";
		}
	}

}

?>
