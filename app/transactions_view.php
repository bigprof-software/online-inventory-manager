<?php
// This script and data application were generated by AppGini 22.12
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/transactions.php');
	include_once(__DIR__ . '/transactions_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('transactions');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'transactions';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`transactions`.`id`" => "id",
		"if(`transactions`.`transaction_date`,date_format(`transactions`.`transaction_date`,'%m/%d/%Y'),'')" => "transaction_date",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "item",
		"IF(    CHAR_LENGTH(`batches1`.`batch_no`), CONCAT_WS('',   `batches1`.`batch_no`), '') /* Batch */" => "batch",
		"IF(    CHAR_LENGTH(`sections1`.`section`), CONCAT_WS('',   `sections1`.`section`), '') /* Storage section */" => "section",
		"`transactions`.`transaction_type`" => "transaction_type",
		"`transactions`.`quantity`" => "quantity",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`transactions`.`id`',
		2 => '`transactions`.`transaction_date`',
		3 => '`items1`.`item`',
		4 => '`batches1`.`batch_no`',
		5 => '`sections1`.`section`',
		6 => 6,
		7 => '`transactions`.`quantity`',
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`transactions`.`id`" => "id",
		"if(`transactions`.`transaction_date`,date_format(`transactions`.`transaction_date`,'%m/%d/%Y'),'')" => "transaction_date",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "item",
		"IF(    CHAR_LENGTH(`batches1`.`batch_no`), CONCAT_WS('',   `batches1`.`batch_no`), '') /* Batch */" => "batch",
		"IF(    CHAR_LENGTH(`sections1`.`section`), CONCAT_WS('',   `sections1`.`section`), '') /* Storage section */" => "section",
		"`transactions`.`transaction_type`" => "transaction_type",
		"`transactions`.`quantity`" => "quantity",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`transactions`.`id`" => "ID",
		"`transactions`.`transaction_date`" => "Transaction date",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "Item",
		"IF(    CHAR_LENGTH(`batches1`.`batch_no`), CONCAT_WS('',   `batches1`.`batch_no`), '') /* Batch */" => "Batch",
		"IF(    CHAR_LENGTH(`sections1`.`section`), CONCAT_WS('',   `sections1`.`section`), '') /* Storage section */" => "Storage section",
		"`transactions`.`transaction_type`" => "Transaction type",
		"`transactions`.`quantity`" => "Quantity",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`transactions`.`id`" => "id",
		"if(`transactions`.`transaction_date`,date_format(`transactions`.`transaction_date`,'%m/%d/%Y'),'')" => "transaction_date",
		"IF(    CHAR_LENGTH(`items1`.`item`), CONCAT_WS('',   `items1`.`item`), '') /* Item */" => "item",
		"IF(    CHAR_LENGTH(`batches1`.`batch_no`), CONCAT_WS('',   `batches1`.`batch_no`), '') /* Batch */" => "batch",
		"IF(    CHAR_LENGTH(`sections1`.`section`), CONCAT_WS('',   `sections1`.`section`), '') /* Storage section */" => "section",
		"`transactions`.`transaction_type`" => "transaction_type",
		"`transactions`.`quantity`" => "quantity",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = ['item' => 'Item', 'batch' => 'Batch', 'section' => 'Storage section', ];

	$x->QueryFrom = "`transactions` LEFT JOIN `items` as items1 ON `items1`.`id`=`transactions`.`item` LEFT JOIN `batches` as batches1 ON `batches1`.`id`=`transactions`.`batch` LEFT JOIN `sections` as sections1 ON `sections1`.`id`=`transactions`.`section` ";
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
	$x->RecordsPerPage = 25;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'transactions_view.php';
	$x->RedirectAfterInsert = 'transactions_view.php?SelectedID=#ID#';
	$x->TableTitle = 'Transactions';
	$x->TableIcon = 'resources/table_icons/book_keeping.png';
	$x->PrimaryKey = '`transactions`.`id`';
	$x->DefaultSortField = '1';
	$x->DefaultSortDirection = 'desc';

	$x->ColWidth = [150, 150, 150, 150, 150, 150, ];
	$x->ColCaption = ['Transaction date', 'Item', 'Batch', 'Storage section', 'Transaction type', 'Quantity', ];
	$x->ColFieldName = ['transaction_date', 'item', 'batch', 'section', 'transaction_type', 'quantity', ];
	$x->ColNumber  = [2, 3, 4, 5, 6, 7, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/transactions_templateTV.html';
	$x->SelectedTemplate = 'templates/transactions_templateTVS.html';
	$x->TemplateDV = 'templates/transactions_templateDV.html';
	$x->TemplateDVP = 'templates/transactions_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: transactions_init
	$render = true;
	if(function_exists('transactions_init')) {
		$args = [];
		$render = transactions_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: transactions_header
	$headerCode = '';
	if(function_exists('transactions_header')) {
		$args = [];
		$headerCode = transactions_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: transactions_footer
	$footerCode = '';
	if(function_exists('transactions_footer')) {
		$args = [];
		$footerCode = transactions_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
