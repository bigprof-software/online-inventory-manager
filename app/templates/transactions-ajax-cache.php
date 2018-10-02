<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function(){
		var tn = 'transactions';

		/* data for selected record, or defaults if none is selected */
		var data = {
			item: <?php echo json_encode(array('id' => $rdata['item'], 'value' => $rdata['item'], 'text' => $jdata['item'])); ?>,
			batch: <?php echo json_encode(array('id' => $rdata['batch'], 'value' => $rdata['batch'], 'text' => $jdata['batch'])); ?>,
			section: <?php echo json_encode(array('id' => $rdata['section'], 'value' => $rdata['section'], 'text' => $jdata['section'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for item */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'item' && d.id == data.item.id)
				return { results: [ data.item ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for batch */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'batch' && d.id == data.batch.id)
				return { results: [ data.batch ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for section */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'section' && d.id == data.section.id)
				return { results: [ data.section ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

