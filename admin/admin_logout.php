<?php
/**
 * admin_logout.php destroys session so administrators can logout
 *
 * Clears session data, forwards user to admin login page upon successful logout  
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.014 2012/06/09
 * @link http://www.newmanix.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see admin_login.php
 * @todo none
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 

startSession(); //wrapper for session_start()
$_SESSION = array();# Setting a session to an empty array safely clears all data

//session_destroy();# can't destroy session as will disable feedback - instead do it on login form!
feedback("Logout Successful!", "notice");
$_SESSION['admin-red'] = THIS_PAGE;
myRedirect($config->adminLogin); # redirect for successful logout
?>
