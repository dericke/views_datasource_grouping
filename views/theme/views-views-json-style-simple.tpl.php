<?php
/**
 * @file views-views-json-style-simple.tpl.php
 * Default template for the Views JSON style plugin using the simple format
 *
 * Variables:
 * - $view: The View object.
 * - $rows: Hierachial array of key=>value pairs to convert to JSON
 * - $options: Array of options for this style
 *
 * @ingroup views_templates
 */

//~ foreach ($rows["nodes"] as $k0 => $v0) {
  //~ echo "<h1>$k0</h1>";
  //~ foreach ($v0 as $k1 => $v1) {
    //~ echo "<h2>$k1</h2>";
    //~ foreach ($v1 as $k2 => $v2) {
      //~ echo "$k2: $v2 <br />";
    //~ }
    //~ echo "<br />";
  //~ }
  //~ echo "<br />";
//~ }
$group = $options["grouping"][0]["field"];
$root = $options["root_object"];
$top_child = $options["top_child_object"];
if ($group) {
$grouped = array();
  foreach ($rows[$root] as $key => $array) { // Values are numeric
    // Grab the grouping field value from inside the 3rd-level array.
    // Creating a label for grouping field will break it for now. @TODO get the pre-labeling value of $rows
    $groupnode = $array[$top_child][$group];
    foreach ($array[$top_child] as $prop => $value) {
      if ($prop != $group) { // Ignore grouped field
        $grouped[$root][$groupnode][$prop][$key] = $value;
      }
    }
  }
$rows = $grouped;
}

//~ echo "<hr />";
//~ foreach ($grouped as $name => $properties) {
  //~ echo "<h2>\$name = $name</h2>";
  //~ foreach ($properties as $propertyk => $propertyv) {
    //~ echo "<dt>\$propertyk = $propertyk</dt><dl><table>";
    //~ foreach ($propertyv as $key => $value) {
      //~ echo "<tr><td>$key</td><td>$value</td></tr>";
    //~ }
    //~ echo "</table></dl>";
  //~ }
//~ }


// Uncomment everything below for prod
$jsonp_prefix = $options['jsonp_prefix'];
//~ //~
if ($view->override_path) {
  // We're inside a live preview where the JSON is pretty-printed.
  $json = _views_json_encode_formatted($rows);
  if ($jsonp_prefix) $json = "$jsonp_prefix($json)";
  print "<code>$json</code>";
}
else {
  $json = _views_json_json_encode($rows, $bitmask);
  if ($options['remove_newlines']) {
     $json = preg_replace(array('/\\\\n/'), '', $json);
  }
//~ //~
  if ($jsonp_prefix) $json = "$jsonp_prefix($json)";
  if ($options['using_views_api_mode']) {
    // We're in Views API mode.
    print $json;
  }
  else {
    // We want to send the JSON as a server response so switch the content
    // type and stop further processing of the page.
    $content_type = ($options['content_type'] == 'default') ? 'application/json' : $options['content_type'];
    drupal_add_http_header("Content-Type", "$content_type; charset=utf-8");
    print $json;
    //Don't think this is needed in .tpl.php files: module_invoke_all('exit');
    exit;
  }
}
