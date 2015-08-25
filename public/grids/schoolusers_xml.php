<?php

require_once("../../includes/initialize.php");

global $session;

if(!$session->is_logged_in())
{
    redirect_to("../../index.php");
}

$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];

$user = User::get_by_id($session->user_id);

$criteria = 
        "".C_SCHOOLUSER_SCHOOLID."
        IN (SELECT ".C_SCHOOLUSER_SCHOOLID." FROM ".T_SCHOOLUSERS."
        WHERE ".C_SCHOOLUSER_USERID."=".$session->user_id."
        AND ".C_SCHOOLUSER_LEVEL."=1)
        GROUP BY ".C_SCHOOLUSER_ID."";

$mysql =  "SELECT * FROM ".T_SCHOOLUSERS." WHERE ".$criteria;

if($user->is_super_admin())
{
    $schoolusers_count = SchoolUser::get_by_sql("SELECT * FROM ".T_SCHOOLUSERS." GROUP BY ".C_SCHOOLUSER_ID);
}
else
{
    $schoolusers_count = SchoolUser::get_by_sql($mysql);
}

$count = count($schoolusers_count);

if( $count > 0 && $limit > 0) 
{ 
	$total_pages = ceil($count / $limit); 
} 
else 
{ 
	$total_pages = 0; 
} 
 
if ($page > $total_pages) $page = $total_pages;
 
$start = $limit * $page - $limit;
 
if($start <0) $start = 0; 
if(!$sidx) $sidx = 1;

$ops = array(
        'eq'=>'=', 
        'ne'=>'<>',
        'lt'=>'<', 
        'le'=>'<=',
        'gt'=>'>', 
        'ge'=>'>=',
        'bw'=>'LIKE',
        'bn'=>'NOT LIKE',
        'in'=>'LIKE', 
        'ni'=>'NOT LIKE', 
        'ew'=>'LIKE', 
        'en'=>'NOT LIKE', 
        'cn'=>'LIKE', 
        'nc'=>'NOT LIKE' 
    );

if($user->is_super_admin())
{
    $where2 = "1=1"." GROUP BY ".C_SCHOOLUSER_ID;
}
else
{
    $where2 = $criteria;
}

if(isset($_GET['searchString']) && isset($_GET['searchField']) && isset($_GET['searchOper']))
{
    $searchString = $_GET['searchString'];
    $searchField = $_GET['searchField'];
    $searchOper = $_GET['searchOper'];

    foreach ($ops as $key=>$value)
    {
        if ($searchOper==$key)
        {
            $ops = $value;
        }
    }

    if($searchOper == 'eq' ) $searchString = $searchString;
    if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
    if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
    if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';

    $where = "$searchField $ops '$searchString'"; 

    $schoolusers = SchoolUser::get_by_sql("SELECT * FROM ".T_SCHOOLUSERS." WHERE ".$where." AND ".$where2." ORDER BY $sidx $sord LIMIT $start , $limit");
}
else
{
    $schoolusers = SchoolUser::get_by_sql("SELECT * FROM ".T_SCHOOLUSERS." WHERE ".$where2." ORDER BY $sidx $sord LIMIT $start , $limit");
}

header("Content-type: text/xml;charset=utf-8");
 
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .=  "<rows>";
$s .= "<page>".$page."</page>";
$s .= "<total>".$total_pages."</total>";
$s .= "<records>".$count."</records>";

foreach($schoolusers as $schooluser) 
{
    $user = User::get_by_id($schooluser->userid);

    if(!$user)
    {
        $user = new User();
    }
    
    $school = School::get_by_id($schooluser->schoolid);

    if(!$school)
    {
        $school = new School();
    }

    $s .= "<row id='". $schooluser->id."'>";
    $s .= "<cell></cell>";      
    $s .= "<cell>". $schooluser->id."</cell>";     
    $s .= "<cell>". $school->id."</cell>";
    $s .= "<cell>". $school->name."</cell>";
    $s .= "<cell>". $user->id."</cell>";
    $s .= "<cell>". $user->username."</cell>";
    $s .= "<cell>". $schooluser->level."</cell>";
    $s .= "<cell>". $schooluser->date."</cell>";
    $s .= "<cell>". $schooluser->pending."</cell>";
    $s .= "<cell>". $schooluser->enabled."</cell>";
    $s .= "<cell></cell>";
    $s .= "</row>";
}

$s .= "</rows>"; 
 
echo $s;
?>