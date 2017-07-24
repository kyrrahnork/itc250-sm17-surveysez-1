<?php
/**
 * error_test.php shows current error & logging settings, plus demonstrates 
 * the current handling of multiple error states 
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.091 2011/06/17
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see config_inc.php 
 * @see header_inc.php
 * @see footer_inc.php 
 * @todo none
 */
 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->titleTag = THIS_PAGE; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php  
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

# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to header_inc.php
?>
<h3 align="center"><?=THIS_PAGE;?></h3>
<?php
if(SHOW_ALL_ERRORS)
{
	echo '<p><b>SHOW_ALL_ERRORS</b> is currently set to <b><font color="red">TRUE</font></b> in <b>config_inc.php</b>, so <b><font color="red">ANYONE</font></b> can see explicit page errors.</p>';
}else{
	echo '<p><b>SHOW_ALL_ERRORS</b> is currently set to <b><font color="green">FALSE</font></b> in <b>config_inc.php</b>, so <b><font color="green">no one</font></b> see explicit page errors.</p>';
}
if(LOG_ALL_ERRORS)
{
	echo '<p><b>LOG_ALL_ERRORS</b> is currently set to <b><font color="green">TRUE</font></b> in <b>config_inc.php</b>, so errors are being logged.</p>';
	echo '<p>View <b>config_inc.php</b> to see where the error_log file is stored.</p>';	
}else{
	echo '<p><b>LOG_ALL_ERRORS</b> is currently set to <b><font color="red">FALSE</font></b> in <b>config_inc.php</b>, so errors are <b><font color="red">NOT</font></b> being logged.</p>';
	echo '<p>View <b>config_inc.php</b> to change error log settings, if desired.</p>';
}

# Create deliberate errors.  
echo $myvar;
include 'fake_inc.php';
$result = 200/0;

get_footer(); #defaults to footer_inc.php
?>
