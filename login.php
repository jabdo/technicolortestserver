<?php


function harden($inpt) {//strips off certain characters
   $inpt = trim($inpt);
   $inpt = stripslashes($inpt);
   $inpt = htmlspecialchars($inpt);
   return $inpt;
}

function setupmongo(){//sets up a mongo connection
   $mongo = new MongoClient("mongodb://technicolor:testbed@localhost/backendtest");
   $collection = $mongo->selectDB('backendtest')->selectCollection('user');
   return $collection;
}

function sortbygroup($col,$groupby) {//sorts the array into groups
   $unique = array();
   $sorted = array();
   foreach ($col as $val) { //get all the unique values
      if (!in_array($val[$groupby],$unique)) {
         $unique[] = $val[$groupby];
      }  
   }
   
   foreach($unique as $key) {
      foreach($col as $val) {//reads off the collection
         if($key == $val[$groupby]) {
            $sorted[] = $val;
         }
      }
   }
   return $sorted;
}
//prints the array
function printcollection($db,$filter='',$filtervalue='',$groupby='') {
   if ($filter <> '') {
      $result = $db->find(array($filter => $filtervalue));
      $groups = array();
   }  else { 
      echo 'No parameters!';
      return;
   }
   $sortedcoll = sortbygroup($result,$groupby);
   echo "id, user, password, location, favoritecolor,";
   foreach ($sortedcoll as $entry) {
      echo "<br />";
      foreach($entry as $i) {
         echo $i . ", ";
      }
   }
}

$users = setupmongo();
$usr = harden($_POST['username']);
$pswd = harden($_POST['password']);

$cursor = $users->findone(array('user' => $usr, 'password' => $pswd));
//check for login info or a cookie
if ((is_null($cursor)) && (!isset($_COOKIE['t_sa']))) {
   echo 'Username or Password was incorrect.';
   exit();
} else {
   if(isset($usr)) { //if logging in for the first time set a cookie
      setcookie('t_sa','active',time()+600);
   }
   $filter = $_GET["filter"];//get the filter values
   $filtervalue = $_GET['filtervalue'];
   $groupby = $_GET['groupby'];
   printcollection($users,$filter,$filtervalue,$groupby);//print everything
}

?>
