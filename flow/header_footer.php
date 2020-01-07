<?php
function page_header()
{
  $server = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  $url = $server;
  $path = parse_url($url, PHP_URL_PATH);
  $parent = dirname($path, 2);
  $styles = ".." . $parent . "/styles/";
  $stylesheet = $styles . "example.css";
  $images = ".." . $parent . "/images/";
  $logo = $images . "green_logo.gif";
  $logo_size = "style=\"width:100px;height:100px\"";

  $script = "<?php header('Access-Control-Allow-Origin: *'); ?>" .
  "<head>" . 
  "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $stylesheet . "\"/>" .
 "</head><body>" .
 "<div id=\"content\">" .
 "<center>" .
 "<img src=\"" . $logo . "\"" .  $logo_size . "\"/>" .
 "<br /><br /><br />" .
 "</center>";
 return $script;
}

function git_footer() {
  $rev = exec('git rev-parse --short HEAD');
  $branch = exec('git rev-parse --abbrev-ref HEAD');
  $gf =  "<footer><hr />Commit: " . $rev . " - Branch: " . $branch . "</footer>";
 
  return $gf;
 }
?>

