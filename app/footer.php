			<!-- Add footer template above here -->
			<div class="clearfix"></div>
			<?php if(!Request::val('Embedded')) { ?>
				<div style="height: 70px;" class="hidden-print"></div>
			<?php } ?>

			<?php if(!Request::val('Embedded')) { ?>
				<!-- AppGini powered by notice -->
				<div style="height: 60px;" class="hidden-print"></div>
				<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation" style="z-index: 800;">
					<p class="navbar-text"><small>
						Powered by <a class="navbar-link" href="https://bigprof.com/appgini/" target="_blank">BigProf AppGini 25.13</a>
					</small></p>
				</nav>
			<?php } ?>

		</div> <!-- /div class="container" -->
		<?php if(!defined('APPGINI_SETUP') && is_file(__DIR__ . '/hooks/footer-extras.php')) { include(__DIR__ . '/hooks/footer-extras.php'); } ?>
	</body>
</html>