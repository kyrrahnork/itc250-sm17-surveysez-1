<?php
/**
 * index.php along with survey_view.php provides a sample web application
 *
 * The difference between demo_list.php and index.php is the reference to the 
 * Pager class which processes a mysqli SQL statement and spans records across multiple  
 * pages. 
 *
 * The associated view page, survey_view.php is virtually identical to demo_view.php. 
 * The only difference is the pager version links to the list pager version to create a 
 * separate application from the original list/view. 
 * 
 * @package SurveySez
 * @author Kyrrah Nork <kyrrahnork@gmail.com>
 * @version 0.1 2017/07/19
 * @link http://kyrrahnork.com/sm17
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see index.php 
 * @todo none
 */
 
# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to index.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "surveys/index.php");
}

$mySurvey = new Survey($myID);

if($mySurvey->IsValid)
{
    $config->titleTag = $mySurvey->Title . " surveys made with love and PHP!";
}
# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
if($mySurvey->IsValid)
{#records exist - show survey!
	echo '
    <h3 align="center">' . $mySurvey->Title . '</h3>
    <p>' . $mySurvey->Description . '</p>
    ';
    
    echo $mySurvey->showQuestions();

}else{//no such survey!
    echo '<div align="center">What! No such survey? There must be a mistake!!</div>';
}
echo '<div align="center"><a href="' . VIRTUAL_PATH . 'surveys/index.php">Back</a></div>';

get_footer(); #defaults to theme footer or footer_inc.php

class Survey
{
    public $SurveyID = 0;
    public $Title = '';
    public $Description = '';
    public $IsValid = false;
    public $Questions = array();
    
    public function __construct($id)
    {
        $id = (int)$id;//cast to int disallows SQL injection
        $sql = "select Title, Description from sm17_surveys where SurveyID = " . $id;
        
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
        
        if(mysqli_num_rows($result) > 0)
        {#records exist - process
           $this->IsValid = true;///record found
           while ($row = mysqli_fetch_assoc($result))
           {
                $this->Title = dbOut($row['Title']);
                $this->Description = dbOut($row['Description']);
           }
        }

        @mysqli_free_result($result); # We're done with the data! 
        
        //------start question class data
        
        $sql = "select Question, QuestionID, Description from sm17_questions where SurveyID = " . $id;
        
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
        
        if(mysqli_num_rows($result) > 0)
        {#records exist - process
           while ($row = mysqli_fetch_assoc($result))
           {
                $this->Questions[] = new Question(dbOut($row['QuestionID']),dbOut($row['Question']),dbOut($row['Description']));
           }
        }

        @mysqli_free_result($result); # We're done with the data!
        
        //------end question class data

    }// end Survey constructor 
    
    public function showQuestions()
    {
        $myReturn='';
        foreach($this->Questions as $question)
        {
            echo '
            <p>QuestionID: ' . $question->QuestionID . ', 
            Text: ' . $question->Text . ', 
            <p>Description: ' . $question->Description . '</p>
            ';
        }       
        return $myReturn;
    }
    
    
}// end Survey class


class Question
{
    public $QuestionID = 0;
    public $Text = '';
    public $Description = '';
    
    public function __construct($QuestionID,$Text,$Description)
    {
        $this->QuestionID = $QuestionID;
        $this->Text = $Text;
        $this->Description = $Description; 
        
        
        
    }//end question constructor 
}//end Question class



