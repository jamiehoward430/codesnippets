<?php
//Please don't load code on the footer of every page if you don't need it on the footer of every page.
//bold("<br>Performance Checker Footer Loaded");
$pluginQueryCounter = $db->getQueryCount();
$mem_usage = memory_get_usage();
$mem_peak = memory_get_peak_usage();
?>
<script type="text/javascript">
window.onload = function () {
	var loadTime = window.performance.timing.domContentLoadedEventEnd-window.performance.timing.navigationStart;
 	var pluginQueryCounter = "<?=$pluginQueryCounter?>";
	var peak = "<?=$mem_peak?>";
	var usage = "<?=$mem_usage?>";
	peak = peak/1024;
	peak = peak.toFixed(2);
	usage = usage/1024;
	usage = usage.toFixed(2);

	
    var formData = {
	  'ms' 						: loadTime,
	  'queries' 				: pluginQueryCounter,
      'p_mem'					: peak,
	  'c_mem'					: usage,
	  'type'					: 'log',
	  'page'					: '<?= $currentPage ?>'
    };

    $.ajax({
      type 		: 'POST',
      url 		: '/usersc/plugins/performancelogger/files/log.php',
      data 		: formData,
      dataType 	: 'json',
    })

    .done(function(data) {

    })
}
</script>
