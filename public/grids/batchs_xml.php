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

$schoolusers  = SchoolUser::getAdminSchools($user->id);

$criteria = 
    "".C_BATCH_ID." IN (SELECT ".C_BATCHUSER_BATCHID." FROM ".T_BATCHUSERS." 
    WHERE ".C_BATCHUSER_USERID."=".$session->user_id." 
    AND ".C_BATCHUSER_LEVEL."=1)";

if(count($schoolusers) > 0)
{
  $criteria =

    "".C_BATCH_SCHOOLID." IN (SELECT ".C_SCHOOLUSER_SCHOOLID." FROM ".T_SCHOOLUSERS." 
    WHERE ".C_SCHOOLUSER_USERID."=".$session->user_id." AND ".C_SCHOOLUSER_LEVEL."=1)"

    ;
}

if($user->is_super_admin())
{
    $batchs_count = Batch::get_by_sql("SELECT * FROM ".T_BATCHS);
}
else
{
    $batchs_count = Batch::get_by_sql("SELECT * FROM ".T_BATCHS." WHERE ".$criteria);
}

$count = count($batchs_count);

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
    $where2 = "1=1";
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

    $batchs = Batch::get_by_sql("SELECT * FROM ".T_BATCHS." WHERE ".$where." AND ".$where2." ORDER BY $sidx $sord LIMIT $start , $limit");
}
else
{
    $batchs = Batch::get_by_sql("SELECT * FROM ".T_BATCHS." WHERE ".$where2." ORDER BY $sidx $sord LIMIT $start , $limit");
}

header("Content-type: text/xml;charset=utf-8");
 
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .=  "<rows>";
$s .= "<page>".$page."</page>";
$s .= "<total>".$total_pages."</total>";
$s .= "<records>".$count."</records>";

foreach($batchs as $batch) 
{
    $school = School::get_by_id($batch->schoolid);

    if(!$school)
    {
        $school = new School();
    }

    $s .= "<row id='". $batch->id."'>";
    $s .= "<cell></cell>";
    $s .= "<cell>". $batch->id."</cell>";           
    $s .= "<cell>". $batch->fromyear."</cell>";
    $s .= "<cell>". ($batch->fromyear + 1)."</cell>";
    $s .= "<cell>". $batch->about."</cell>";
    $s .= "<cell>". $school->id."</cell>";
    $s .= "<cell>". $school->name."</cell>";
    $s .= "<cell>". $batch->date."</cell>";
    $s .= "<cell>". $batch->comments."</cell>";
    $s .= "<cell>". $batch->pending."</cell>";
    $s .= "<cell>". $batch->enabled."</cell>";
    $s .= "<cell></cell>";
    $s .= "</row>";
}

$s .= "</rows>"; 
 
echo $s;
?>