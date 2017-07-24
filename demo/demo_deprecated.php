<?php
/**
 * demo_idb.php is both a test page for your IDB shared mysqli connection, and a starting point for 
 * building DB applications using IDB connections
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.09 2011/05/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php  
 * @see header_inc.php
 * @see footer_inc.php 
 * @todo none
 */
 
/* DEPRECATED CODE HANDLING HERE - MUST GO BEFORE CALL TO CONFIG AS OVERRIDES DEFAULT HANDLER */ 
//$error_reporting = 2047; #loosens error reporting for this page due to deprecation
//$error_handler = 'none'; #overrides 'custom' error handler for this page due to deprecation
  
# '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

$config->titleTag = smartTitle(); #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaDescription = smartTitle() . ' - ' . $config->metaDescription; 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

# SQL statement - PREFIX is optional way to distinguish your app
$sql = "select FirstName, LastName, Email from test_Customers";
//END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to header_inc.php

?>
<h3 align="center"><?php echo $config->titleTag; ?></h3>
<p>This page shows how to bypass the default error handling for a single page without effecting the error handling for 
all other pages.</p>
<p>This is good for working with code found in apps you need to integrate have old (deprecated) PHP inside!</p>
<p>Below I'm trying to place deprecated code, but having trouble finding some!!</p>
<?php

class Book{ var $title;} #var is deprecated

$myBook = new Book;

$myBook->title = "My Book title";
echo "<p>My book object title is: <b>$myBook->title</b>!</p>";

get_footer(); #defaults to footer_inc.php
?>
