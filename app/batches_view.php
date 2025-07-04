<?php
// This script and data application was generated by AppGini, https://bigprof.com/appgini
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/batches.php');
	include_once(__DIR__ . '/batches_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('batches');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'batches';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`batches`.`id`" => "id",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "item",
		"IF(    CHAR_LENGTH(`suppliers1`.`supplier`), CONCAT_WS('',   `suppliers1`.`supplier`), '') /* Supplier */" => "supplier",
		"`batches`.`batch_no`" => "batch_no",
		"if(`batches`.`manufacturing_date`,date_format(`batches`.`manufacturing_date`,'%m/%d/%Y'),'')" => "manufacturing_date",
		"if(`batches`.`expiry_date`,date_format(`batches`.`expiry_date`,'%m/%d/%Y'),'')" => "expiry_date",
		"`batches`.`balance`" => "balance",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`batches`.`id`',
		2 => '`items1`.`item`',
		3 => '`suppliers1`.`supplier`',
		4 => 4,
		5 => '`batches`.`manufacturing_date`',
		6 => '`batches`.`expiry_date`',
		7 => '`batches`.`balance`',
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`batches`.`id`" => "id",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "item",
		"IF(    CHAR_LENGTH(`suppliers1`.`supplier`), CONCAT_WS('',   `suppliers1`.`supplier`), '') /* Supplier */" => "supplier",
		"`batches`.`batch_no`" => "batch_no",
		"if(`batches`.`manufacturing_date`,date_format(`batches`.`manufacturing_date`,'%m/%d/%Y'),'')" => "manufacturing_date",
		"if(`batches`.`expiry_date`,date_format(`batches`.`expiry_date`,'%m/%d/%Y'),'')" => "expiry_date",
		"`batches`.`balance`" => "balance",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`batches`.`id`" => "ID",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "Item",
		"IF(    CHAR_LENGTH(`suppliers1`.`supplier`), CONCAT_WS('',   `suppliers1`.`supplier`), '') /* Supplier */" => "Supplier",
		"`batches`.`batch_no`" => "Batch code",
		"`batches`.`manufacturing_date`" => "Manufacturing date",
		"`batches`.`expiry_date`" => "Expiry date",
		"`batches`.`balance`" => "Balance",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`batches`.`id`" => "id",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "item",
		"IF(    CHAR_LENGTH(`suppliers1`.`supplier`), CONCAT_WS('',   `suppliers1`.`supplier`), '') /* Supplier */" => "supplier",
		"`batches`.`batch_no`" => "batch_no",
		"if(`batches`.`manufacturing_date`,date_format(`batches`.`manufacturing_date`,'%m/%d/%Y'),'')" => "manufacturing_date",
		"if(`batches`.`expiry_date`,date_format(`batches`.`expiry_date`,'%m/%d/%Y'),'')" => "expiry_date",
		"`batches`.`balance`" => "balance",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = ['item' => 'Item', 'supplier' => 'Supplier', ];

	$x->QueryFrom = "`batches` LEFT JOIN `items` as items1 ON `items1`.`id`=`batches`.`item` LEFT JOIN `suppliers` as suppliers1 ON `suppliers1`.`id`=`batches`.`supplier` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm['view'] == 0 ? 1 : 0);
	$x->AllowDelete = $perm['delete'];
	$x->AllowMassDelete = true;
	$x->AllowInsert = $perm['insert'];
	$x->AllowUpdate = $perm['edit'];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->AllowAdminShowSQL = showSQL();
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'batches_view.php';
	$x->RedirectAfterInsert = 'batches_view.php?SelectedID=#ID#';
	$x->TableTitle = 'Batches';
	$x->TableIcon = 'resources/table_icons/box_closed.png';
	$x->PrimaryKey = '`batches`.`id`';
	$x->DefaultSortField = '1';
	$x->DefaultSortDirection = 'desc';

	$x->ColWidth = [150, 150, 150, 150, 150, 150, ];
	$x->ColCaption = ['Item', 'Supplier', 'Batch code', 'Manufacturing date', 'Expiry date', 'Balance', ];
	$x->ColFieldName = ['item', 'supplier', 'batch_no', 'manufacturing_date', 'expiry_date', 'balance', ];
	$x->ColNumber  = [2, 3, 4, 5, 6, 7, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/batches_templateTV.html';
	$x->SelectedTemplate = 'templates/batches_templateTVS.html';
	$x->TemplateDV = 'templates/batches_templateDV.html';
	$x->TemplateDVP = 'templates/batches_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: batches_init
	$render = true;
	if(function_exists('batches_init')) {
		$args = [];
		$render = batches_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// column sums
	if(strpos($x->HTML, '<!-- tv data below -->')) {
		// if printing multi-selection TV, calculate the sum only for the selected records
		$record_selector = Request::val('record_selector');
		if(Request::val('Print_x') && is_array($record_selector)) {
			$QueryWhere = '';
			foreach($record_selector as $id) {   // get selected records
				if($id != '') $QueryWhere .= "'" . makeSafe($id) . "',";
			}
			if($QueryWhere != '') {
				$QueryWhere = 'where `batches`.`id` in ('.substr($QueryWhere, 0, -1).')';
			} else { // if no selected records, write the where clause to return an empty result
				$QueryWhere = 'where 1=0';
			}
		} else {
			$QueryWhere = $x->QueryWhere;
		}

		$sumQuery = "SELECT SUM(`batches`.`balance`) FROM {$x->QueryFrom} {$QueryWhere}";
		$res = sql($sumQuery, $eo);
		if($row = db_fetch_row($res)) {
			$sumRow = '<tr class="success sum">';
			if(!Request::val('Print_x')) $sumRow .= '<th class="text-center sum">&sum;</th>';
			$sumRow .= '<td class="batches-item sum"></td>';
			$sumRow .= '<td class="batches-supplier sum"></td>';
			$sumRow .= '<td class="batches-batch_no sum"></td>';
			$sumRow .= '<td class="batches-manufacturing_date sum"></td>';
			$sumRow .= '<td class="batches-expiry_date sum"></td>';
			$sumRow .= "<td class=\"batches-balance text-right sum locale-float\">{$row[0]}</td>";
			$sumRow .= '</tr>';

			$x->HTML = str_replace('<!-- tv data below -->', '', $x->HTML);
			$x->HTML = str_replace('<!-- tv data above -->', $sumRow, $x->HTML);
		}
	}

	// hook: batches_header
	$headerCode = '';
	if(function_exists('batches_header')) {
		$args = [];
		$headerCode = batches_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php');
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: batches_footer
	$footerCode = '';
	if(function_exists('batches_footer')) {
		$args = [];
		$footerCode = batches_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php');
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
