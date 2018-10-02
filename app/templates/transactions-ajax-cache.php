<script>
	$j(function(){
		var tn = 'transactions';

		/* data for selected record, or defaults if none is selected */
		var data = {
			item: { id: '<?php echo $rdata['item']; ?>', value: '<?php echo $rdata['item']; ?>', text: '<?php echo $jdata['item']; ?>' },
			batch: { id: '<?php echo $rdata['batch']; ?>', value: '<?php echo $rdata['batch']; ?>', text: '<?php echo $jdata['batch']; ?>' },
			section: { id: '<?php echo $rdata['section']; ?>', value: '<?php echo $rdata['section']; ?>', text: '<?php echo $jdata['section']; ?>' }
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

