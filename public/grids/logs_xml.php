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
        "".C_LOGS_USER_ID."

        IN (SELECT ".C_SCHOOLUSER_USERID." FROM ".T_SCHOOLUSERS."
        WHERE ".C_SCHOOLUSER_USERID."=".$session->user_id."
        AND ".C_SCHOOLUSER_LEVEL."=1)

        OR ".C_LOGS_USER_ID." IN (SELECT ".C_BATCHUSER_USERID." FROM ".T_BATCHUSERS."
        WHERE ".C_BATCHUSER_USERID."=".$session->user_id."
        AND ".C_BATCHUSER_LEVEL."=1)

        OR ".C_LOGS_USER_ID." IN (SELECT ".C_SECTIONUSER_USERID." FROM ".T_SECTIONUSERS."
        WHERE ".C_SECTIONUSER_USERID."=".$session->user_id."
        AND ".C_SECTIONUSER_LEVEL."=1)

        GROUP BY ".C_LOGS_ID."";

$mysql =  "SELECT * FROM ".T_LOGS." WHERE ".$criteria;

if($user->is_super_admin())
{
    $logs_count = Log::get_by_sql("SELECT * FROM ".T_LOGS." GROUP BY ".C_LOGS_ID);
}
else
{
    $logs_count = Log::get_by_sql($mysql);
}

$count = count($logs_count);

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
    $where2 = "1=1 "." GROUP BY ".C_LOGS_ID;
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

    $logs = Log::get_by_sql("SELECT * FROM ".T_LOGS." WHERE ".$where." ORDER BY $sidx $sord LIMIT $start , $limit");
}
else
{
    $logs = Log::get_by_sql("SELECT * FROM ".T_LOGS." ORDER BY $sidx $sord LIMIT $start , $limit");
}

header("Content-type: text/xml;charset=utf-8");
 
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .=  "<rows>";
$s .= "<page>".$page."</page>";
$s .= "<total>".$total_pages."</total>";
$s .= "<records>".$count."</records>";

foreach($logs as $log) 
{
    $user = User::get_by_id($log->user_id);

    if(!$user)
    {
        $user = new User();
        $user->id = "LOST";
        $user->username = "LOST";
    }

    $s .= "<row id='". $log->id."'>";
    $s .= "<cell></cell>";           
    $s .= "<cell>". $user->id."</cell>";
    $s .= "<cell>". $user->username."</cell>";
    $s .= "<cell>". $log->ip."</cell>";
    $s .= "<cell>". $log->platform."</cell>";
    $s .= "<cell>". $log->date."</cell>";
    $s .= "<cell>". $log->action."</cell>";
    $s .= "<cell></cell>";
    $s .= "</row>";
}

$s .= "</rows>"; 
 
echo $s;
?>