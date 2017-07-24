<?php
/**
 * demo_list_meaningful.php is a proof of concept of dynamic row creation for a list page
 *
 * This example is a further illustration of dynamic row creation started with demo_list_meaningful_multi.php
 *
 * This version uses curvy corners, a JavaScript solution for providing curved corners on 
 * <div> tags without images
 *
 * This version includes thumbnail support, added in the nmUpload package, 
 * and Paging, added in the nmPager package
 * 
 * @package nmUpload
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.031 2012/03/11
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see demo_view_meaningful.php
 * @see upload_form.php
 * @see upload_execute.php
 * @todo none
 */
 
 # '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 
 
# SQL statement
$sql = "select MuffinName, MuffinID, Price, Description from test_Muffins";

#Fills <title> tag. If left empty will default to $config->TitleTag in config_inc.php  
$config->TitleTag = 'Muffins made with love & PHP in Seattle';

#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
$config->metaDescription = 'Seattle Central\'s ITC280 Class Muffins are made with pure PHP! ' . $config->metaDescription;
$config->metaKeywords = 'Muffins,PHP,Fun,Bran,Regular,Regular Expressions,'. $config->metaKeywords;
$config->metaRobots = ''; #use default in config_inc.php - set to 'no index,no follow' during development

# SQL statement
$sql = "select MuffinName, MuffinID, Description, Price from test_Muffins";

define("COLS",1);  # The maximum number of columns to show per list page

$config->loadhead = 
'
<style type="text/css">
	.myborder{border:1px solid #52F3FF; height:100px;}
</style> 
';

#reference images for pager
$prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
$next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';

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

get_header(); #defaults to theme header or header_inc.php
?>
<h3 align="center"><?=smartTitle();?></h3>

<p>This page, along with demo_view_meaningful.php, demonstrate a List/View web application.</p>
<p>This version adds controls the number of columns visible per page.</p>

<?php
$myPager = new Pager(2,'',$prev,$next,''); # Create instance of new 'pager' class
$sql = $myPager->loadSQL($sql);  #load SQL, add offset
   
# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if(mysqli_num_rows($result) > 0)
{#records exist - process
	if($myPager->showTotal()==1){$itemz = "muffin";}else{$itemz = "muffins";} 
	echo '<div align="center">We have ' . $myPager->showTotal() . ' ' . $itemz . '!</div>';
	
	$tdWidth = number_format(100/COLS,0); # Here we determine the number of columns we'll be using
	$pos = 0;
	
  	echo '<table align="center" border="0" width="90%" style="border-collapse:collapse" cellpadding="2" cellspacing="2"><tr>';
    while ($row = mysqli_fetch_assoc($result))
	{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		$pos++;
		#creates a meaningful image prefix out of table name and name of item
		$imagePrefix  = createImagePrefix('muffins',dbOut($row['MuffinName'])); 
     	echo '<td width="' . $tdWidth . '%"><div class="myborder">';
     	echo '<img src="' . VIRTUAL_PATH . 'upload/' . $imagePrefix . dbOut($row['MuffinID']) . '_thumb.jpg" hspace="5" vspace="5" align="middle" />';
		echo '<a href="' . VIRTUAL_PATH . 'demo/demo_view_meaningful.php?id=' . dbOut($row['MuffinID']) . '">' . dbOut($row['MuffinName']) . '</a>';
		echo ' <i>only</i> <font color="red">$' . money_format("%(#10n",dbOut($row['Price'])) . '</font><br />';
		echo dbOut($row['Description']) .'</div></td>';
		if ($pos%COLS === 0 && is_array($row)){echo '</tr><tr>';}
	}
	while ($pos%COLS)
	{//loop to fill in final row
	  echo '<td>&nbsp;</td>';
	  $pos++;
    }
  	echo "</tr></table>";

	echo $myPager->showNAV(); //show paging nav, only if enough records	 
}else{#no records
    echo "<div align=center>What! No muffins?  There must be a mistake!!</div>";	
}
@mysqli_free_result($result);

get_footer(); #defaults to theme footer or footer_inc.php

function createImagePrefix($tableName,$itemName)
{
		$itemName = strtolower($itemName); #to lower case
		$itemName = str_replace(' ','-',$itemName); #replace spaces with dashes
		return $tableName . '_' . $itemName . '_';
}
?>
