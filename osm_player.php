<?php
/**
 * http://oocn.eu/osm_player.php
 * OPM Open Media Player
 *
 * @author Rui Felizes  <ruifelizes@oocn.eu>
 * @copyright Copyright (c) 2014, Rui Felizes
 * @license http://www.opensource.org/licenses/bsd-license.html BSD License
 * 
 */
session_start();
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);
require('lib.php');
//require($_SERVER['DOCUMENT_ROOT'].'/oper/lib/lib.php');
// set source file name and path
//if (isset($_REQUEST['x'])) {
if (isset($_SESSION['x'])) { 
// echo $_REQUEST['x']; 

//$source = $_REQUEST['x'];
$source = $_SESSION['x']; 
//After finishing the code:
unset($_SESSION['x']); 
//$source = "0960315.txt";
}
else {
$source = "empty.txt";
if (file_exists($source)) {
	//do nothing
} else {
    file_put_contents($source, '');
}

}


// read raw text as array
$raw = file($source) or die("Cannot read file");

// retrieve first and second lines (title and author)
//$slug = array_shift($raw);
//$byline = array_shift($raw);

// join remaining data into string
$data = join('', $raw);
$data = utf8_encode($data);
$begin='<div><a class="btn btn-danger"><i class="fa fa-trash-o fa-lg"></i></a><span contenteditable="true">';
$midle='&#13;&#10;</span></div><div><a class="btn btn-danger" href="#"><i class="fa fa-trash-o fa-lg"></i></a><span contenteditable="true">';
$end='&#13;&#10;</span></div>';
$html = convert($data,$begin,$midle,$end);
//$html = convert($data);
//$html = nl2p($data);
// replace special characters with HTML entities
// replace line breaks with <br />
//$html = nl2br(htmlspecialchars($data),false);

// replace multiple spaces with single spaces
$html = preg_replace('/\s\s+/', ' ', $html);

// replace URLs with <a href...> elements
$html = preg_replace('/\s(\w+:\/\/)(\S+)/', ' <a href="\\1\\2" target="_blank">\\1\\2</a>', $html);

// start building output page
// add page header
$root=$_SERVER['DOCUMENT_ROOT'];
//echo $root;
/*
$html_link='<link rel="stylesheet" href="' . $root . '/font-awesome/css/font-awesome.css">';

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="jasny-bootstrap/css/jasny-bootstrap.min.css">
<!---->';
*/
$server= 'http://' . $_SERVER['SERVER_NAME'];
$html_link[]='<link rel="stylesheet" href="' . $server . '/oper/font-awesome/css/font-awesome.css">';$html_link[]='<link rel="stylesheet" href="' . $server . '/oper/bootstrap/css/bootstrap.min.css">';
$html_link[]='<link rel="stylesheet" href="' . $server . '/oper/jasny-bootstrap/css/jasny-bootstrap.min.css">';
$html_link[]='<script src="' . $server . '/oper/jquery/jquery-2.1.0.min.js"></script>';
$html_link[]='<!--<<script src="' . $server . '/oper/jquery/jquery.min.js"></script>-->';
$html_link[]='<script src="' . $server . '/oper/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>';$html_link[]='<!---->';

$html_links=implode(PHP_EOL,$html_link);


$output =<<< HEADER
<!DOCTYPE html>
<head>
$html_links

<style>
div{padding: 10px 10px 10px 10px;text-align:justify;line-height:150%;}
span{padding: 0px 0px 0px 10px;}
.slug {font-size: 15pt; font-weight: bold}
.byline { font-style: italic }
</style>

</head>

<body>
HEADER;

// add page content
$output .= <<< xxx

<div class="input-group margin-bottom-sm">
<span onclick="saveEdits()" class="input-group-addon"><i class="glyphicon glyphicon-floppy-save"></i></span>
<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i></span>
<input id="filenametosave" class="form-control" type="text" placeholder="Nome do Ficheiro">
</div>
<div class="fileinput fileinput-new input-group" data-provides="fileinput">
  <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
  <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="..."></span>
  <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
</div>

xxx;
$output .= <<< xxx
<div class="input-group margin-bottom-sm">
<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="submit" name="submit" value="Submit">
<input type="file" name="file" id="file">
</form>
</div>
xxx;


//$output .= "<div class='slug'>$slug</div>";
//$output .= "<div class='byline'>$byline</div><p />";
$output .= "<div>$html</div>";

// add page footer
$output .=<<< FOOTER
</body>
</html>
<script>
$( "a" ).click(function() {
  $(this).parent().remove();
//  $(this).next("span").remove();
//  $(this).remove();
});
function saveEdits() {

var htmlString =$( "span" ).text();

var ficheiro=$("#filenametosave").val(); 

$.post("savetofile.php", {content: htmlString, filenametosave: ficheiro}, function(data) {
alert("Data Loaded: " + data);

} );

}
</script>
FOOTER;

// display in browser
echo $output;

// AND/OR 

// write output to a new .html file
file_put_contents(basename($source, substr($source, strpos($source, '.'))) . ".html", $output) or die("Cannot write file");
?>