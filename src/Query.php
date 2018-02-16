<?
/*!
 * Copyright (c) 2016 - Eric Mantoani <eu@eric.com.br>
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

use Solr\HttpRequest;

class Query extends HttpRequest{

  var $solrUrl;

  var $q;
  var $fq;
  var $sort;
  var $start;
  var $rows;
  var $fl;
  var $wt = "json";
  var $facet;
  var $facetField;
  var $facetQuery;
  var $sfield;
  var $pt;
  var $d;
  var $hl;
  var $hlField;
  var $hlQ;

  function __construct($solrUrl){
    $this->reset();
    $this->solrUrl = $solrUrl;
  }

  function Search($Solr){
    $this->reset();
    foreach($Solr as $k => $v){
      $this->$k = $v;
    }

    return $this->query();
  }

  function query(){
    $url = $this->solrUrl."/select/";
    $url .= $this->q();
    $url .= $this->fq();
    $url .= $this->sort();
    $url .= $this->start();
    $url .= $this->rows();
    $url .= $this->fl();
    $url .= $this->wt();
    $url .= $this->facet();
    $url .= $this->group();
    $url .= $this->sfield();
    $url .= $this->pt();
    $url .= $this->d();
    $url .= $this->hl();
    $url .= $this->hlField();
    $url .= $this->hlQ();

    $Response = new parent("get", $url);

    if(isset($_GET['debugg'])){
      debugg($Response);
      debugg($Response->getResponse());
      echo '<pre><a href="'.$url.'" target="_blank">'.$url.'</a><br /><br /></pre>';
    }

    // echo $url; exit;

    $this->reset();
    return json_decode($Response->getResponse());
  }

  function q(){
    if(empty($this->q)){
      return "?q=*";
    }

    return "?q=".urlencode($this->q);
  }

  function fq(){
    if(empty($this->fq)){
      return "";
    }

    return "&fq=".urlencode($this->fq);
  }

  function start(){
    return "&start=".$this->start;
  }

  function rows(){
    return "&rows=".$this->rows;
  }

  function fl(){
    if(empty($this->fl)){
      return "";
    }

    return "&fl=".$this->fl;
  }

  function sfield(){
    if(empty($this->sfield)){
      return "";
    }

    return "&sfield=".urlencode($this->sfield);
  }

  function pt(){
    if(empty($this->pt)){
      return "";
    }

    return "&pt=".urlencode($this->pt);
  }

  function d(){
    if(empty($this->d)){
      return "";
    }

    return "&d=".urlencode($this->d);
  }

  function hl(){
    if(empty($this->hl)){
      return "";
    }

    return "&hl=".urlencode($this->hl);
  }

  function hlQ(){
    if(empty($this->hlQ)){
      return "";
    }

    return "&hl.q=".urlencode($this->hlQ);
  }

  function hlField(){
    if(empty($this->hlField)){
      return "";
    }

    return "&hl.field=".urlencode($this->hlField);
  }

  function wt(){
    return "&wt=".$this->wt;
  }

  function sort(){
    if(empty($this->sort)){
      return "";
    }

    return "&sort=".urlencode($this->sort);
  }

  function facet(){
    $facet = "";
    if(!empty($this->facet)){
      $facet .= "&facet=".$this->facet;
    }

    if(!empty($this->facetField)){
      $facet .= "&facet.field=".$this->facetField;
    }

    if(!empty($this->facetQuery)){
      $facet .= "&facet.query=".$this->facetQuery;
    }

    return $facet;
  }

  function group(){
    $group = "";
    if(!empty($this->group)){
      $group .= "&group=".$this->group;
    }

    if(!empty($this->groupField)){
      $group .= "&group.field=".$this->groupField;

      $group .= "&group.limit=".$this->rows;
      $group .= "&group.offset=".$this->start;
      $group .= "&group.sort=".urlencode($this->sort);
    }

    return $group;
  }

  function _or($array){
    $or = "";
    foreach($array as $item){
      if($item === reset($array)){
        if(strlen($or) == 0){
          $or .= $item;
        }
      } else {
        $or .= " OR ".$item;
      }
    }

    return $or;
  }

  function fetchFacetResult($f){
    $Facet = new stdClass();

    for($i=0; $i<count($f); $i++){
      $Facet->$f[$i] = $f[$i+1];
      $i++;
    }

    return $Facet;
  }

  function reset(){
    $this->q = "";
    $this->fq = "";
    $this->wt = "json";
    $this->start = 0;
    $this->rows = 30;
    $this->fl = "";
    $this->sort = "";
    $this->pt = "";
    $this->d = "";
    $this->sfield = "";
    $this->hl = "";
    $this->hlField = "";
    $this->hlQ = "";
  }

}

?>
