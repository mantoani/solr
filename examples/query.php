<?php
	
	require __DIR__."/../vendor/autoload.php";

	$solrCore = "http://localhost:8983/solr/orders";

	$Solr = new Solr\Query($solrCore);

	$Solr->q = "*:*";
	$Solr->rows = 3;

	echo json_encode($Solr->query());

?>