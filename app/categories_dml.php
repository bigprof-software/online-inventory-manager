<?php

// Data functions (insert, update, delete, form) for table categories

// This script and data application was generated by AppGini, https://bigprof.com/appgini
// Download AppGini for free from https://bigprof.com/appgini/download/

function categories_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('categories');
	if(!$arrPerm['insert']) {
		$error_message = $Translation['no insert permission'];
		return false;
	}

	$data = [
		'category' => Request::val('category', ''),
	];

	// record owner is current user
	$recordOwner = getLoggedMemberID();

	$recID = tableInsert('categories', $data, $recordOwner, $error_message);

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID')) && $recID !== false)
		categories_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function categories_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);
	$currentUsername = getLoggedMemberID();
	$errorMessage = '';

	// launch requests, asynchronously
	curl_batch($requests);
}

function categories_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('categories', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: categories_before_delete
	if(function_exists('categories_before_delete')) {
		$args = [];
		if(!categories_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: ''
			);
	}

	// child table: items
	$res = sql("SELECT `id` FROM `categories` WHERE `id`='{$selected_id}'", $eo);
	$id = db_fetch_row($res);
	$rires = sql("SELECT COUNT(1) FROM `items` WHERE `category`='" . makeSafe($id[0]) . "'", $eo);
	$rirow = db_fetch_row($rires);
	$childrenATag = '<a class="alert-link" href="items_view.php?filterer_category=' . urlencode($id[0]) . '">%s</a>';
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace('<RelatedRecords>', sprintf($childrenATag, $rirow[0]), $RetMsg);
		$RetMsg = str_replace(['[<TableName>]', '<TableName>'], sprintf($childrenATag, 'items'), $RetMsg);
		return $RetMsg;
	} elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation['confirm delete'];
		$RetMsg = str_replace('<RelatedRecords>', sprintf($childrenATag, $rirow[0]), $RetMsg);
		$RetMsg = str_replace(['[<TableName>]', '<TableName>'], sprintf($childrenATag, 'items'), $RetMsg);
		$RetMsg = str_replace('<Delete>', '<input type="button" class="btn btn-danger" value="' . html_attr($Translation['yes']) . '" onClick="window.location = `categories_view.php?SelectedID=' . urlencode($selected_id) . '&delete_x=1&confirmed=1&csrf_token=' . urlencode(csrf_token(false, true)) . (Request::val('Embedded') ? '&Embedded=1' : '') . '`;">', $RetMsg);
		$RetMsg = str_replace('<Cancel>', '<input type="button" class="btn btn-success" value="' . html_attr($Translation[ 'no']) . '" onClick="window.location = `categories_view.php?SelectedID=' . urlencode($selected_id) . (Request::val('Embedded') ? '&Embedded=1' : '') . '`;">', $RetMsg);
		return $RetMsg;
	}

	sql("DELETE FROM `categories` WHERE `id`='{$selected_id}'", $eo);

	// hook: categories_after_delete
	if(function_exists('categories_after_delete')) {
		$args = [];
		categories_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='categories' AND `pkValue`='{$selected_id}'", $eo);
}

function categories_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('categories', $selected_id, 'edit')) return false;

	$data = [
		'category' => Request::val('category', ''),
	];

	// get existing values
	$old_data = getRecord('categories', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: categories_before_update
	if(function_exists('categories_before_update')) {
		$args = ['old_data' => $old_data];
		if(!categories_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'categories',
		backtick_keys_once($set),
		['`id`' => $selected_id],
		$error_message
	)) {
		echo $error_message;
		echo '<a href="categories_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}


	update_calc_fields('categories', $data['selectedID'], calculated_fields()['categories']);

	// hook: categories_after_update
	if(function_exists('categories_after_update')) {
		if($row = getRecord('categories', $data['selectedID'])) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['id'];
		$args = ['old_data' => $old_data];
		if(!categories_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update record update timestamp
	set_record_owner('categories', $selected_id);
}

function categories_form($selectedId = '', $allowUpdate = true, $allowInsert = true, $allowDelete = true, $separateDV = true, $templateDV = '', $templateDVP = '') {
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selectedId. If $selectedId
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;
	$eo = ['silentErrors' => true];
	$noUploads = $row = $urow = $jsReadOnly = $jsEditable = $lookups = null;
	$noSaveAsCopy = false;
	$hasSelectedId = strlen($selectedId) > 0;

	// mm: get table permissions
	$arrPerm = getTablePermissions('categories');
	$allowInsert = ($arrPerm['insert'] ? true : false);
	$allowUpdate = $hasSelectedId && check_record_permission('categories', $selectedId, 'edit');
	$allowDelete = $hasSelectedId && check_record_permission('categories', $selectedId, 'delete');

	if(!$allowInsert && !$hasSelectedId)
		// no insert permission and no record selected
		// so show access denied error -- except if TVDV: just hide DV
		return $separateDV ? $Translation['tableAccessDenied'] : '';

	if($hasSelectedId && !check_record_permission('categories', $selectedId, 'view'))
		return $Translation['tableAccessDenied'];

	// print preview?
	$dvprint = $hasSelectedId && Request::val('dvprint_x') != '';

	$showSaveNew = !$dvprint && ($allowInsert && !$hasSelectedId);
	$showSaveChanges = !$dvprint && $allowUpdate && $hasSelectedId;
	$showDelete = !$dvprint && $allowDelete && $hasSelectedId;
	$showSaveAsCopy = !$dvprint && ($allowInsert && $hasSelectedId && !$noSaveAsCopy);
	$fieldsAreEditable = !$dvprint && (($allowInsert && !$hasSelectedId) || ($allowUpdate && $hasSelectedId) || $showSaveAsCopy);


	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');

	if($hasSelectedId) {
		if(!($row = getRecord('categories', $selectedId))) {
			return error_message($Translation['No records found'], 'categories_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
	}

	ob_start();
	?>

	<script>
		// initial lookup values

		$j(function() {
			setTimeout(function() {
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_clean());


	// code for template based detail view forms

	// open the detail view template
	if($dvprint) {
		$template_file = is_file("./{$templateDVP}") ? "./{$templateDVP}" : './templates/categories_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	} else {
		$template_file = is_file("./{$templateDV}") ? "./{$templateDV}" : './templates/categories_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Category details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', (Request::val('Embedded') ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($showSaveNew) {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
	} elseif($showSaveAsCopy) {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if(Request::val('Embedded')) {
		$backAction = 'AppGini.closeParentModal(); return false;';
	} else {
		$backAction = 'return true;';
	}

	if($hasSelectedId) {
		if(!Request::val('Embedded')) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($allowUpdate)
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);

		if($allowDelete)
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		// if not in embedded mode and user has insert only but no view/update/delete,
		// remove 'back' button
		if(
			$allowInsert
			&& !$allowUpdate && !$allowDelete && !$arrPerm['view']
			&& !Request::val('Embedded')
		)
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
		elseif($separateDV)
			$templateCode = str_replace(
				'<%%DESELECT_BUTTON%%>',
				'<button
					type="submit"
					class="btn btn-default"
					id="deselect"
					name="deselect_x"
					value="1"
					onclick="' . $backAction . '"
					title="' . html_attr($Translation['Back']) . '">
						<i class="glyphicon glyphicon-chevron-left"></i> ' .
						$Translation['Back'] .
				'</button>',
				$templateCode
			);
		else
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(!$fieldsAreEditable) {
		$jsReadOnly = '';
		$jsReadOnly .= "\t\$j('#category').replaceWith('<div class=\"form-control-static\" id=\"category\">' + (\$j('#category').val() || '') + '</div>');\n";
		$jsReadOnly .= "\t\$j('.select2-container').hide();\n";

		$noUploads = true;
	} else {
		// temporarily disable form change handler till time and datetime pickers are enabled
		$jsEditable = "\t\$j('form').eq(0).data('already_changed', true);";
		$jsEditable .= "\t\$j('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = [];
	foreach($lookup_fields as $luf => $ptfc) {
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if(($pt_perm['view'] && isDetailViewEnabled($ptfc[0])) || $pt_perm['edit']) {
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] /* && !Request::val('Embedded')*/) {
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-default add_new_parent" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus text-success"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(id)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(category)%%>', '', $templateCode);

	// process values
	if($hasSelectedId) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(category)%%>', safe_html($urow['category']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(category)%%>', html_attr($row['category']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(category)%%>', urlencode($urow['category']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(category)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(category)%%>', urlencode(''), $templateCode);
	}

	// process translations
	$templateCode = parseTemplate($templateCode);

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if(Request::val('dvprint_x') == '') {
		$templateCode .= "\n\n<script>\$j(function() {\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption) {
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$hasSelectedId) {
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields
	$filterField = Request::val('FilterField');
	$filterOperator = Request::val('FilterOperator');
	$filterValue = Request::val('FilterValue');

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('categories');
	if($hasSelectedId) {
		$jdata = get_joined_record('categories', $selectedId);
		if($jdata === false) $jdata = get_defaults('categories');
		$rdata = $row;
	}
	$templateCode .= loadView('categories-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: categories_dv
	if(function_exists('categories_dv')) {
		$args = [];
		categories_dv(($hasSelectedId ? $selectedId : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}