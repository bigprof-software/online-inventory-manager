<?php if(!empty($_REQUEST['Filter_x']) && !intval($_REQUEST['advanced_search_mode'])) { ?>
	<script>
		$j(function() {
			$j('.navbar-fixed-bottom small').after(
				'<small>' +
				'<i class="glyphicon glyphicon-asterisk"></i> ' +
				'This search page was created by ' +
				'<a href="https://bigprof.com/appgini/applications/search-page-maker-plugin" target="_blank" title="Search Page Maker plugin for AppGini">SPM plugin for AppGini</a>.' +
				'</small>'
			);
		})
	</script>
<?php } ?>

<?php if(strpos($_SERVER['PHP_SELF'], 'summary-reports-')) { ?>
	<script>
		$j(function() {
			$j('.navbar-fixed-bottom small').after(
				'<small>' +
				'<i class="glyphicon glyphicon-asterisk"></i> ' +
				'This report was created by ' +
				'<a href="https://bigprof.com/appgini/applications/summary-reports-plugin" target="_blank" title="Summary Reports plugin for AppGini">Summary Reports plugin for AppGini</a>.' +
				'</small>'
			);
		})
	</script>
<?php } ?>

<?php if(strpos($_SERVER['PHP_SELF'], 'hooks/calendar-')) { ?>
	<script>
		$j(function() {
			$j('.navbar-fixed-bottom small').after(
				'<small>' +
				'<i class="glyphicon glyphicon-asterisk"></i> ' +
				'This calendar was created by ' +
				'<a href="https://bigprof.com/appgini/applications/calendar-plugin" target="_blank" title="Calendar plugin for AppGini">Calendar plugin for AppGini</a>.' +
				'</small>'
			);
		})
	</script>
<?php } ?>

<script>
	$j(function() {
		// if we're not in home page, abort this block
		if(!$j('.row.table_links').length) return;

		// create a grid of 2:1 on md+ screens
		$j('.container > .homepage-links > a.collapser').eq(0)
			.before(
				'<div class="row">' +
					'<div class="homepage-collapse-links col-md-9"></div>' +
					'<div class="homepage-app-model col-md-3"></div>' +
				'</div>'
			);

		// move table links to the left section of the grid
		$j('.container > .homepage-links > a.collapser').each(function() {
			var link = $j(this);
			var content = link.next();
			link.appendTo('.homepage-collapse-links');
			content.appendTo('.homepage-collapse-links');
		});

		// show app model diagram in .homepage-app-model
		$j(`
			<figure>
				<img src="https://cdn.bigprof.com/appgini-open-source-apps/oim/online-inventory-manager-process-flow-diagram.jpg" style="margin-top: 15px;" class="img-responsive" alt="Online Inventory Manager process flow diagram" />
				<figcaption><abbr title="Online Inventory Manager">OIM</abbr> helps you manage your inventory of perishable items. Suppliers deliver batches of items to one of your storage locations. A batch is a specific quantity of some item that has an expiry date. <br><br>Batches of items are added to your inventory in an <i>incoming transaction</i>. When items are withdrawn from inventory (for sales/consumption), this is done through an <i>outgoing transaction</i>. <br><br>
				The balance of each inventory item is updated automatically when a transaction is recorded.
				</figcaption>
			</figure>
		`).appendTo('.homepage-app-model');
	})
</script>