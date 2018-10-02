<script>
	$j(function(){
		var tn = 'batches';

		/* data for selected record, or defaults if none is selected */
		var data = {
			item: { id: '<?php echo $rdata['item']; ?>', value: '<?php echo $rdata['item']; ?>', text: '<?php echo $jdata['item']; ?>' },
			supplier: { id: '<?php echo $rdata['supplier']; ?>', value: '<?php echo $rdata['supplier']; ?>', text: '<?php echo $jdata['supplier']; ?>' }
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

		/* saved value for supplier */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'supplier' && d.id == data.supplier.id)
				return { results: [ data.supplier ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

