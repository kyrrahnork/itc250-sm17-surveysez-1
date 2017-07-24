<?php
/**
 * demo_view_meaningful.php along with demo_list_meaningful_pager.php demonstrates
 * records paging with a list/view application
 *
 * This version uses a page specific function createImagePrefix() at the bottom of this page 
 * to create a meaningful image prefix from the 
 * 
 * @package nmUpload
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.031 2012/03/11
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see demo_list_meaningful.php
 * @see upload_form.php
 * @see upload_execute.php 
 * @todo align table name and/or item name changes with image name
 */
 
 # '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 

# check variable of item passed in - if invalid data, forcibly redirect back to list page 
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "demo/demo_list_meaningful.php");
}

# sql statement to select individual item
$sql = "select MuffinName,Description,MetaDescription,MetaKeywords,Price from test_Muffins where MuffinID = " . $myID;

$foundRecord = FALSE; # Will change to true, if record found!
   
# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if(mysqli_num_rows($result) > 0)
{#records exist - process
	   $foundRecord = TRUE;	
	   while ($row = mysqli_fetch_assoc($result))
	   {
			$MuffinName = dbOut($row['MuffinName']);
			$Description = dbOut($row['Description']);
			$Price = dbOut($row['Price']);
			$MetaDescription = dbOut($row['MetaDescription']);
			$MetaKeywords = dbOut($row['MetaKeywords']);
	   }
}

@mysqli_free_result($result); # We're done with the data!

if($foundRecord)
{#only load data if record found
	$config->titleTag = $MuffinName . " muffins made with PHP & love!"; #overwrite PageTitle with Muffin info!
	#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
	$config->metaDescription = $MetaDescription . ' Seattle Central\'s ITC280 Class Muffins are made with pure PHP! ' . $config->metaDescription;
	$config->metaKeywords = $MetaKeywords . ',Muffins,PHP,Fun,Bran,Regular,Regular Expressions,'. $config->metaKeywords;
}

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

<p>This page, along with demo_list_meaningful.php, demonstrate a paging List/View web application.</p>
<p>This version incorporates image uploads with meaningful file names for better search engine indexing.</p>

<?php
#creates a meaningful image prefix out of table name and name of item
$imagePrefix  = createImagePrefix('muffins',$MuffinName); 

if($foundRecord)
{#records exist - show muffin!
?>
	<h3 align="center">A Yummy <?=$MuffinName;?> Muffin!</h3>
	<div align="center"><a href="<?=VIRTUAL_PATH;?>demo/demo_list_meaningful.php">More Muffins?!?</a></div>
	<table align="center">
		<tr>
			<td><img src="<?=VIRTUAL_PATH;?>upload/<?=$imagePrefix;?><?=$myID;?>.jpg" />

					<?php
					if(startSession() && isset($_SESSION["AdminID"]))
					{# only admins can see 'peek a boo' link:
					
					     //this line commented as we need to pass on image prefix (see below)
						//echo '<div align="center"><a href="' . VIRTUAL_PATH . 'upload_form.php?' . $_SERVER['QUERY_STRING'] . '">UPLOAD IMAGE</a></div>';
						
						# if you wish to overwrite any of these options on the view page, 
						# you may uncomment this area, and provide different parameters:						
						echo '<div align="center"><a href="' . VIRTUAL_PATH . 'upload_form.php?' . $_SERVER['QUERY_STRING']; 
						echo '&imagePrefix=' . $imagePrefix; #we need to pass on image prefix as defined on this page!
						echo '&uploadFolder=upload/';
						echo '&extension=.jpg';
						echo '&createThumb=TRUE';
						echo '&thumbWidth=50';
						echo '&thumbSuffix=_thumb';
						echo '&sizeBytes=100000';
						echo '">UPLOAD IMAGE</a></div>';
												
						

					}
					if(isset($_GET['msg']))
					{# msg on querystring implies we're back from uploading new image
						$msgSeconds = (int)$_GET['msg'];
						$currSeconds = time();
						if(($msgSeconds + 2)> $currSeconds)
						{//link only visible once, due to time comparison of qstring data to current timestamp
							echo '<div align="center"><script type="text/javascript">';
							echo 'document.write("<form><input type=button value=\'IMAGE UPLOADED! CLICK TO VIEW!\' onClick=history.go()></form>")</scr';
							echo 'ipt></div>';
						}
					}
				?>
			</td>	
			<td>We make fresh <?=$MuffinName;?> muffins daily!</td>
		</tr>
		<tr>
			<td colspan="2">
				<blockquote><?=$Description;?></blockquote>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<h3><i>ONLY!!:</i> <font color="red">$<?=number_format($Price,2);?></font></h3>
			</td>
		</tr>
	</table>
<?
}else{//no such muffin!
    echo '<div align="center">What! No such muffin? There must be a mistake!!</div>';
    echo '<div align="center"><a href="' . VIRTUAL_PATH . 'demo/demo_list_meaningful.php">Another Muffin?</a></div>';
}


get_footer(); #defaults to theme footer or footer_inc.php

function createImagePrefix($tableName,$itemName)
{
		$itemName = strtolower($itemName); #to lower case
		$itemName = str_replace(' ','-',$itemName); #replace spaces with dashes
		return $tableName . '_' . $itemName . '_';
}
?>
