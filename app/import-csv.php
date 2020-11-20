<?php
	define('PREPEND_PATH', '');
	$app_dir = dirname(__FILE__);
	include_once("{$app_dir}/lib.php");

	// accept a record as an assoc array, return transformed row ready to insert to table
	$transformFunctions = [
		'transactions' => function($data, $options = []) {
			if(isset($data['transaction_date'])) $data['transaction_date'] = guessMySQLDateTime($data['transaction_date']);
			if(isset($data['item'])) $data['item'] = pkGivenLookupText($data['item'], 'transactions', 'item');
			if(isset($data['batch'])) $data['batch'] = pkGivenLookupText($data['batch'], 'transactions', 'batch');
			if(isset($data['section'])) $data['section'] = pkGivenLookupText($data['section'], 'transactions', 'section');

			return $data;
		},
		'batches' => function($data, $options = []) {
			if(isset($data['item'])) $data['item'] = pkGivenLookupText($data['item'], 'batches', 'item');
			if(isset($data['supplier'])) $data['supplier'] = pkGivenLookupText($data['supplier'], 'batches', 'supplier');
			if(isset($data['manufacturing_date'])) $data['manufacturing_date'] = guessMySQLDateTime($data['manufacturing_date']);
			if(isset($data['expiry_date'])) $data['expiry_date'] = guessMySQLDateTime($data['expiry_date']);

			return $data;
		},
		'suppliers' => function($data, $options = []) {

			return $data;
		},
		'categories' => function($data, $options = []) {

			return $data;
		},
		'items' => function($data, $options = []) {
			if(isset($data['category'])) $data['category'] = pkGivenLookupText($data['category'], 'items', 'category');

			return $data;
		},
		'sections' => function($data, $options = []) {

			return $data;
		},
	];

	// accept a record as an assoc array, return a boolean indicating whether to import or skip record
	$filterFunctions = [
		'transactions' => function($data, $options = []) { return true; },
		'batches' => function($data, $options = []) { return true; },
		'suppliers' => function($data, $options = []) { return true; },
		'categories' => function($data, $options = []) { return true; },
		'items' => function($data, $options = []) { return true; },
		'sections' => function($data, $options = []) { return true; },
	];

	/*
	Hook file for overwriting/amending $transformFunctions and $filterFunctions:
	hooks/import-csv.php
	If found, it's included below

	The way this works is by either completely overwriting any of the above 2 arrays,
	or, more commonly, overwriting a single function, for example:
		$transformFunctions['tablename'] = function($data, $options = []) {
			// new definition here
			// then you must return transformed data
			return $data;
		};

	Another scenario is transforming a specific field and leaving other fields to the default
	transformation. One possible way of doing this is to store the original transformation function
	in GLOBALS array, calling it inside the custom transformation function, then modifying the
	specific field:
		$GLOBALS['originalTransformationFunction'] = $transformFunctions['tablename'];
		$transformFunctions['tablename'] = function($data, $options = []) {
			$data = call_user_func_array($GLOBALS['originalTransformationFunction'], [$data, $options]);
			$data['fieldname'] = 'transformed value';
			return $data;
		};
	*/

	@include("{$app_dir}/hooks/import-csv.php");

	$ui = new CSVImportUI($transformFunctions, $filterFunctions);
