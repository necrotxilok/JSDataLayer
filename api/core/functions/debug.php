<?php 

// ---------------------------------------

/**
 * Debug Functions
 */
function pr($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function prd($data)
{
	pr($data);
	die;
}

// ---------------------------------------
