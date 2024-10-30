<?php
/*
Plugin Name: Heavyweight Categories
Plugin URI: http://elliottback.com/wp/archives/2005/01/18/heavyweight-categories/
Description: Template tag to display a weighted list of category links
Version: 1.3
Author: Elliott Back
Author URI: http://elliottback.com
*/

function heavyweight_categories($smallest=10, $largest=48, $unit="pt", $exclude='') {
	$cats = list_cats(1, '', 'name', 'asc', '', 1, 0, 1, 1, 0, 0, 1, 0, 1, '', '', $exclude, 0);
	$cats = explode("\n", $cats);

	// Initialize arrays
	$counts = array();
	$data = array();

	// Fill arrays
	foreach ($cats as $cat) {
		// Get link and category
		preg_match('/a href="(.*?)"/si', $cat, $regs);
		$catlink = $regs[1];

		// Get name
		preg_match('/<a[^>]*>([^<]*)<\/a[\s]*>/i', $cat, $regs);
		$catname = $regs[1];

		// Get count
		preg_match('/([\d]+)/si', strip_tags($cat), $regs);
		$count = $regs[1];

		// If already done, skip
		if(!array_key_exists($catname, $data) && !array_key_exists($catname, $counts)){
			$counts[$catname] = $count;
			$data[$catname] = $catlink;
		}
	}

	$spread = max($counts) - min($counts);

	if ($spread <= 0) { $spread = 1; };
	$fontspread = $largest - $smallest;
	$fontstep = $spread / $fontspread;
	if ($fontspread <= 0) { $fontspread = 1; }
    
	foreach ($counts as $catname => $count) {
		echo '<a href="' . $data[$catname] . '" title="' . $count . ' entries" style="font-size: ' . ($smallest + ($count/$fontstep)) . $unit . ';">' . $catname .'</a> ' . "\n";
	}
}
?>