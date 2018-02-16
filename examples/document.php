<?php
	
	require __DIR__."/../vendor/autoload.php";

	$solrCore = "http://localhost:8983/solr/orders";

	$doc = new Solr\Document($solrCore);

	$doc->addField('name', 'value');

	print_r($doc);

?>