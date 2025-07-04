<?php

// Data functions (insert, update, delete, form) for table batches

// This script and data application was generated by AppGini, https://bigprof.com/appgini
// Download AppGini for free from https://bigprof.com/appgini/download/

function batches_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('batches');
	if(!$arrPerm['insert']) {
		$error_message = $Translation['no insert permission'];
		return false;
	}

	$data = [
		'item' => Request::lookup('item', ''),
		'supplier' => Request::lookup('supplier', ''),
		'batch_no' => Request::val('batch_no', ''),
		'manufacturing_date' => Request::dateComponents('manufacturing_date', '1'),
		'expiry_date' => Request::dateComponents('expiry_date', '1'),
	];

	// record owner is current user
	$recordOwner = getLoggedMemberID();

	$recID = tableInsert('batches', $data, $recordOwner, $error_message);

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID')) && $recID !== false)
		batches_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function batches_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);
	$currentUsername = getLoggedMemberID();
	$errorMessage = '';

	// launch requests, asynchronously
	curl_batch($requests);
}

function batches_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('batches', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: batches_before_delete
	if(function_exists('batches_before_delete')) {
		$args = [];
		if(!batches_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: ''
			);
	}

	// child table: transactions
	$res = sql("SELECT `id` FROM `batches` WHERE `id`='{$selected_id}'", $eo);
	$id = db_fetch_row($res);
	$rires = sql("SELECT COUNT(1) FROM `transactions` WHERE `batch`='" . makeSafe($id[0]) . "'", $eo);
	$rirow = db_fetch_row($rires);
	$childrenATag = '<a class="alert-link" href="transactions_view.php?filterer_batch=' . urlencode($id[0]) . '">%s</a>';
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace('<RelatedRecords>', sprintf($childrenATag, $rirow[0]), $RetMsg);
		$RetMsg = str_replace(['[<TableName>]', '<TableName>'], sprintf($childrenATag, 'transactions'), $RetMsg);
		return $RetMsg;
	} elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation['confirm delete'];
		$RetMsg = str_replace('<RelatedRecords>', sprintf($childrenATag, $rirow[0]), $RetMsg);
		$RetMsg = str_replace(['[<TableName>]', '<TableName>'], sprintf($childrenATag, 'transactions'), $RetMsg);
		$RetMsg = str_replace('<Delete>', '<input type="button" class="btn btn-danger" value="' . html_attr($Translation['yes']) . '" onClick="window.location = `batches_view.php?SelectedID=' . urlencode($selected_id) . '&delete_x=1&confirmed=1&csrf_token=' . urlencode(csrf_token(false, true)) . (Request::val('Embedded') ? '&Embedded=1' : '') . '`;">', $RetMsg);
		$RetMsg = str_replace('<Cancel>', '<input type="button" class="btn btn-success" value="' . html_attr($Translation[ 'no']) . '" onClick="window.location = `batches_view.php?SelectedID=' . urlencode($selected_id) . (Request::val('Embedded') ? '&Embedded=1' : '') . '`;">', $RetMsg);
		return $RetMsg;
	}

	sql("DELETE FROM `batches` WHERE `id`='{$selected_id}'", $eo);

	// hook: batches_after_delete
	if(function_exists('batches_after_delete')) {
		$args = [];
		batches_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='batches' AND `pkValue`='{$selected_id}'", $eo);
}

function batches_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('batches', $selected_id, 'edit')) return false;

	$data = [
		'item' => Request::lookup('item', ''),
		'supplier' => Request::lookup('supplier', ''),
		'batch_no' => Request::val('batch_no', ''),
		'manufacturing_date' => Request::dateComponents('manufacturing_date', ''),
		'expiry_date' => Request::dateComponents('expiry_date', ''),
	];

	// get existing values
	$old_data = getRecord('batches', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: batches_before_update
	if(function_exists('batches_before_update')) {
		$args = ['old_data' => $old_data];
		if(!batches_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'batches',
		backtick_keys_once($set),
		['`id`' => $selected_id],
		$error_message
	)) {
		echo $error_message;
		echo '<a href="batches_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}


	update_calc_fields('batches', $data['selectedID'], calculated_fields()['batches']);

	// hook: batches_after_update
	if(function_exists('batches_after_update')) {
		if($row = getRecord('batches', $data['selectedID'])) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['id'];
		$args = ['old_data' => $old_data];
		if(!batches_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update record update timestamp
	set_record_owner('batches', $selected_id);
}

function batches_form($selectedId = '', $allowUpdate = true, $allowInsert = true, $allowDelete = true, $separateDV = true, $templateDV = '', $templateDVP = '') {
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
	$arrPerm = getTablePermissions('batches');
	$allowInsert = ($arrPerm['insert'] ? true : false);
	$allowUpdate = $hasSelectedId && check_record_permission('batches', $selectedId, 'edit');
	$allowDelete = $hasSelectedId && check_record_permission('batches', $selectedId, 'delete');

	if(!$allowInsert && !$hasSelectedId)
		// no insert permission and no record selected
		// so show access denied error -- except if TVDV: just hide DV
		return $separateDV ? $Translation['tableAccessDenied'] : '';

	if($hasSelectedId && !check_record_permission('batches', $selectedId, 'view'))
		return $Translation['tableAccessDenied'];

	// print preview?
	$dvprint = $hasSelectedId && Request::val('dvprint_x') != '';

	$showSaveNew = !$dvprint && ($allowInsert && !$hasSelectedId);
	$showSaveChanges = !$dvprint && $allowUpdate && $hasSelectedId;
	$showDelete = !$dvprint && $allowDelete && $hasSelectedId;
	$showSaveAsCopy = !$dvprint && ($allowInsert && $hasSelectedId && !$noSaveAsCopy);
	$fieldsAreEditable = !$dvprint && (($allowInsert && !$hasSelectedId) || ($allowUpdate && $hasSelectedId) || $showSaveAsCopy);

	$filterer_item = Request::val('filterer_item');
	$filterer_supplier = Request::val('filterer_supplier');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: item
	$combo_item = new DataCombo;
	// combobox: supplier
	$combo_supplier = new DataCombo;
	// combobox: manufacturing_date
	$combo_manufacturing_date = new DateCombo;
	$combo_manufacturing_date->DateFormat = "mdy";
	$combo_manufacturing_date->MinYear = defined('batches.manufacturing_date.MinYear') ? constant('batches.manufacturing_date.MinYear') : 1900;
	$combo_manufacturing_date->MaxYear = defined('batches.manufacturing_date.MaxYear') ? constant('batches.manufacturing_date.MaxYear') : 2100;
	$combo_manufacturing_date->DefaultDate = parseMySQLDate('1', '1');
	$combo_manufacturing_date->MonthNames = $Translation['month names'];
	$combo_manufacturing_date->NamePrefix = 'manufacturing_date';
	// combobox: expiry_date
	$combo_expiry_date = new DateCombo;
	$combo_expiry_date->DateFormat = "mdy";
	$combo_expiry_date->MinYear = defined('batches.expiry_date.MinYear') ? constant('batches.expiry_date.MinYear') : 1900;
	$combo_expiry_date->MaxYear = defined('batches.expiry_date.MaxYear') ? constant('batches.expiry_date.MaxYear') : 2100;
	$combo_expiry_date->DefaultDate = parseMySQLDate('1', '1');
	$combo_expiry_date->MonthNames = $Translation['month names'];
	$combo_expiry_date->NamePrefix = 'expiry_date';

	if($hasSelectedId) {
		if(!($row = getRecord('batches', $selectedId))) {
			return error_message($Translation['No records found'], 'batches_view.php', false);
		}
		$combo_item->SelectedData = $row['item'];
		$combo_supplier->SelectedData = $row['supplier'];
		$combo_manufacturing_date->DefaultDate = $row['manufacturing_date'];
		$combo_expiry_date->DefaultDate = $row['expiry_date'];
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
		$combo_item->SelectedData = $filterer_item;
		$combo_supplier->SelectedData = $filterer_supplier;
	}
	$combo_item->HTML = '<span id="item-container' . $rnd1 . '"></span><input type="hidden" name="item" id="item' . $rnd1 . '" value="' . html_attr($combo_item->SelectedData) . '">';
	$combo_item->MatchText = '<span id="item-container-readonly' . $rnd1 . '"></span><input type="hidden" name="item" id="item' . $rnd1 . '" value="' . html_attr($combo_item->SelectedData) . '">';
	$combo_supplier->HTML = '<span id="supplier-container' . $rnd1 . '"></span><input type="hidden" name="supplier" id="supplier' . $rnd1 . '" value="' . html_attr($combo_supplier->SelectedData) . '">';
	$combo_supplier->MatchText = '<span id="supplier-container-readonly' . $rnd1 . '"></span><input type="hidden" name="supplier" id="supplier' . $rnd1 . '" value="' . html_attr($combo_supplier->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_item__RAND__ = { text: "", value: "<?php echo addslashes($hasSelectedId ? $urow['item'] : htmlspecialchars($filterer_item, ENT_QUOTES)); ?>"};
		AppGini.current_supplier__RAND__ = { text: "", value: "<?php echo addslashes($hasSelectedId ? $urow['supplier'] : htmlspecialchars($filterer_supplier, ENT_QUOTES)); ?>"};

		$j(function() {
			setTimeout(function() {
				if(typeof(item_reload__RAND__) == 'function') item_reload__RAND__();
				if(typeof(supplier_reload__RAND__) == 'function') supplier_reload__RAND__();
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function item_reload__RAND__() {
		<?php if($fieldsAreEditable) { ?>

			$j("#item-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_item__RAND__.value, t: 'batches', f: 'item' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="item"]').val(resp.results[0].id);
							$j('[id=item-container-readonly__RAND__]').html('<span class="match-text" id="item-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=items_view_parent]').hide(); } else { $j('.btn[id=items_view_parent]').show(); }


							if(typeof(item_update_autofills__RAND__) == 'function') item_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'batches', f: 'item' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_item__RAND__.value = e.added.id;
				AppGini.current_item__RAND__.text = e.added.text;
				$j('[name="item"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=items_view_parent]').hide(); } else { $j('.btn[id=items_view_parent]').show(); }


				if(typeof(item_update_autofills__RAND__) == 'function') item_update_autofills__RAND__();
			});

			if(!$j("#item-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_item__RAND__.value, t: 'batches', f: 'item' },
					success: function(resp) {
						$j('[name="item"]').val(resp.results[0].id);
						$j('[id=item-container-readonly__RAND__]').html('<span class="match-text" id="item-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=items_view_parent]').hide(); } else { $j('.btn[id=items_view_parent]').show(); }

						if(typeof(item_update_autofills__RAND__) == 'function') item_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_item__RAND__.value, t: 'batches', f: 'item' },
				success: function(resp) {
					$j('[id=item-container__RAND__], [id=item-container-readonly__RAND__]').html('<span class="match-text" id="item-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=items_view_parent]').hide(); } else { $j('.btn[id=items_view_parent]').show(); }

					if(typeof(item_update_autofills__RAND__) == 'function') item_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function supplier_reload__RAND__() {
		<?php if($fieldsAreEditable) { ?>

			$j("#supplier-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_supplier__RAND__.value, t: 'batches', f: 'supplier' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="supplier"]').val(resp.results[0].id);
							$j('[id=supplier-container-readonly__RAND__]').html('<span class="match-text" id="supplier-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=suppliers_view_parent]').hide(); } else { $j('.btn[id=suppliers_view_parent]').show(); }


							if(typeof(supplier_update_autofills__RAND__) == 'function') supplier_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'batches', f: 'supplier' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_supplier__RAND__.value = e.added.id;
				AppGini.current_supplier__RAND__.text = e.added.text;
				$j('[name="supplier"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=suppliers_view_parent]').hide(); } else { $j('.btn[id=suppliers_view_parent]').show(); }


				if(typeof(supplier_update_autofills__RAND__) == 'function') supplier_update_autofills__RAND__();
			});

			if(!$j("#supplier-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_supplier__RAND__.value, t: 'batches', f: 'supplier' },
					success: function(resp) {
						$j('[name="supplier"]').val(resp.results[0].id);
						$j('[id=supplier-container-readonly__RAND__]').html('<span class="match-text" id="supplier-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=suppliers_view_parent]').hide(); } else { $j('.btn[id=suppliers_view_parent]').show(); }

						if(typeof(supplier_update_autofills__RAND__) == 'function') supplier_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_supplier__RAND__.value, t: 'batches', f: 'supplier' },
				success: function(resp) {
					$j('[id=supplier-container__RAND__], [id=supplier-container-readonly__RAND__]').html('<span class="match-text" id="supplier-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=suppliers_view_parent]').hide(); } else { $j('.btn[id=suppliers_view_parent]').show(); }

					if(typeof(supplier_update_autofills__RAND__) == 'function') supplier_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_clean());


	// code for template based detail view forms

	// open the detail view template
	if($dvprint) {
		$template_file = is_file("./{$templateDVP}") ? "./{$templateDVP}" : './templates/batches_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	} else {
		$template_file = is_file("./{$templateDV}") ? "./{$templateDV}" : './templates/batches_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Batch details', $templateCode);
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
		$jsReadOnly .= "\t\$j('#item').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\t\$j('#item_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\t\$j('#supplier').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\t\$j('#supplier_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\t\$j('#batch_no').replaceWith('<div class=\"form-control-static\" id=\"batch_no\">' + (\$j('#batch_no').val() || '') + '</div>');\n";
		$jsReadOnly .= "\t\$j('#manufacturing_date').prop('readonly', true);\n";
		$jsReadOnly .= "\t\$j('#manufacturing_dateDay, #manufacturing_dateMonth, #manufacturing_dateYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\t\$j('#expiry_date').prop('readonly', true);\n";
		$jsReadOnly .= "\t\$j('#expiry_dateDay, #expiry_dateMonth, #expiry_dateYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\t\$j('.select2-container').hide();\n";

		$noUploads = true;
	} else {
		// temporarily disable form change handler till time and datetime pickers are enabled
		$jsEditable = "\t\$j('form').eq(0).data('already_changed', true);";
		$jsEditable .= "\t\$j('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(item)%%>', $combo_item->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(item)%%>', $combo_item->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(item)%%>', urlencode($combo_item->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(supplier)%%>', $combo_supplier->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(supplier)%%>', $combo_supplier->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(supplier)%%>', urlencode($combo_supplier->MatchText), $templateCode);
	$templateCode = str_replace(
		'<%%COMBO(manufacturing_date)%%>',
		(!$fieldsAreEditable ?
			'<div class="form-control-static">' . $combo_manufacturing_date->GetHTML(true) . '</div>' :
			$combo_manufacturing_date->GetHTML()
		), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(manufacturing_date)%%>', $combo_manufacturing_date->GetHTML(true), $templateCode);
	$templateCode = str_replace(
		'<%%COMBO(expiry_date)%%>',
		(!$fieldsAreEditable ?
			'<div class="form-control-static">' . $combo_expiry_date->GetHTML(true) . '</div>' :
			$combo_expiry_date->GetHTML()
		), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(expiry_date)%%>', $combo_expiry_date->GetHTML(true), $templateCode);

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = ['item' => ['items', 'Item'], 'supplier' => ['suppliers', 'Supplier'], ];
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
	$templateCode = str_replace('<%%UPLOADFILE(item)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(supplier)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(batch_no)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(manufacturing_date)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(expiry_date)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(balance)%%>', '', $templateCode);

	// process values
	if($hasSelectedId) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(item)%%>', safe_html($urow['item']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(item)%%>', html_attr($row['item']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(item)%%>', urlencode($urow['item']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(supplier)%%>', safe_html($urow['supplier']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(supplier)%%>', html_attr($row['supplier']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(supplier)%%>', urlencode($urow['supplier']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(batch_no)%%>', safe_html($urow['batch_no']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(batch_no)%%>', html_attr($row['batch_no']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(batch_no)%%>', urlencode($urow['batch_no']), $templateCode);
		$templateCode = str_replace('<%%VALUE(manufacturing_date)%%>', app_datetime($row['manufacturing_date']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(manufacturing_date)%%>', urlencode(app_datetime($urow['manufacturing_date'])), $templateCode);
		$templateCode = str_replace('<%%VALUE(expiry_date)%%>', app_datetime($row['expiry_date']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(expiry_date)%%>', urlencode(app_datetime($urow['expiry_date'])), $templateCode);
		$templateCode = str_replace('<%%VALUE(balance)%%>', safe_html($urow['balance']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(balance)%%>', urlencode($urow['balance']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(item)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(item)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(supplier)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(supplier)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(batch_no)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(batch_no)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(manufacturing_date)%%>', '1', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(manufacturing_date)%%>', urlencode('1'), $templateCode);
		$templateCode = str_replace('<%%VALUE(expiry_date)%%>', '1', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(expiry_date)%%>', urlencode('1'), $templateCode);
		$templateCode = str_replace('<%%VALUE(balance)%%>', '0.00', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(balance)%%>', urlencode('0.00'), $templateCode);
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
	$rdata = $jdata = get_defaults('batches');
	if($hasSelectedId) {
		$jdata = get_joined_record('batches', $selectedId);
		if($jdata === false) $jdata = get_defaults('batches');
		$rdata = $row;
	}
	$templateCode .= loadView('batches-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: batches_dv
	if(function_exists('batches_dv')) {
		$args = [];
		batches_dv(($hasSelectedId ? $selectedId : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}