<?php
// $Id $
/**
 * @file views-view-json.tpl.php
 * View template to render view fields as JSON. Currently only supports the Exhibit format.
 *
 * - $view: The view in use.
 * - $rows: The raw result objects from the query, with all data it fetched.
 * - $options: The options for the style passed in from the UI.
 *
 * @ingroup views_templates
 * @see views_json.views.inc
 */
 
//print_r($view);
//print_r($rows);
//print_r($options);

$items = array();
foreach($rows as $row) {
//  print_r($row).EOL;
  $items[] = explode("|", trim($row));
  
} 
//print_r($items);
//foreach ($items as $item) { 
//  print_r($item).PHP_EOL;
//	foreach($item as $itemfield) {
//		print($itemfield);
//		$itemfieldarray = explode(":", $itemfield);
//		print_r($itemfieldarray).PHP_EOL;
//		$label = $itemfieldarray[0]; $value=$itemfieldarray[1];
//		print $label." : ".$value;
//	}
//}

if ($options['format'] == 'Exhibit') json_exhibit_render($items);

function json_exhibit_render($items) {
  define('EXHIBIT_DATE_FORMAT', '%Y-%m-%d %H:%M:%S');
  $json = "{\n".str_repeat(" ", 4).'"items"'." : ". "  [";
  foreach ($items as $item) {
  	$json.="\n".str_repeat(" ", 8)."{\n"; 
  	foreach ($item as $itemfield) {
  		$itemfieldarray = explode(":", $itemfield);
  		$label = $itemfieldarray[0]; $value=$itemfieldarray[1];        
      $value = preg_replace('/<.*?>/', '', $value); // strip html tags
      $value = str_replace(array("\r", "\n", ','), ' ', $value); // strip line breaks and commas
      $value = str_replace('"', '""', $value); // escape " characters
      $value = decode_entities($value);
      $json.=str_repeat(" ", 8).$label. " ".": ".'"'.$value.'"'."\n";
  	}
  	$json.=str_repeat(" ", 8)."},\n";
  }
  $json.=str_repeat(" ", 4)."]\n}";
  /*
   * The following will cause an error in a live view preview - comment out if
   * debugging in there.
   */
  drupal_set_header('Content-Type: text/javascript');
  print $json;
  module_invoke_all('exit');
  exit;
}
