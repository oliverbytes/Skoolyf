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
$batchusers   = BatchUser::getAdminBatchs($user->id);

$criteria =

"".C_SECTION_ID." IN (SELECT ".C_SECTIONUSER_SECTIONID." FROM ".T_SECTIONUSERS." 
WHERE ".C_SECTIONUSER_USERID."=".$session->user_id." AND ".C_SECTIONUSER_LEVEL."=1)"

;

if(count($batchusers) > 0)
{
  $criteria =

    "".C_SECTION_BATCHID." IN (SELECT ".C_BATCHUSER_BATCHID." FROM ".T_BATCHUSERS." 
    WHERE ".C_BATCHUSER_USERID."=".$session->user_id." AND ".C_BATCHUSER_LEVEL."=1)"

    ;
}

if(count($schoolusers) > 0)
{
  $criteria =

    "".C_SECTION_SCHOOLID." IN (SELECT ".C_SCHOOLUSER_SCHOOLID." FROM ".T_SCHOOLUSERS." 
    WHERE ".C_SCHOOLUSER_USERID."=".$session->user_id." AND ".C_SCHOOLUSER_LEVEL."=1)"

    ;
}

if($user->is_super_admin())
{
    $sections_count = Section::get_by_sql("SELECT * FROM ".T_SECTIONS);
}
else
{
    $sections_count = Section::get_by_sql("SELECT * FROM ".T_SECTIONS." WHERE ".$criteria);
}

$count = count($sections_count);

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

    $sections = Section::get_by_sql("SELECT * FROM ".T_SECTIONS." WHERE ".$where." AND ".$where2." ORDER BY $sidx $sord LIMIT $start , $limit");
}
else
{
    $sections = Section::get_by_sql("SELECT * FROM ".T_SECTIONS." WHERE ".$where2." ORDER BY $sidx $sord LIMIT $start , $limit");
}

header("Content-type: text/xml;charset=utf-8");
 
$s = "<?xml version='1.0' encoding='utf-8'?>";
$s .=  "<rows>";
$s .= "<page>".$page."</page>";
$s .= "<total>".$total_pages."</total>";
$s .= "<records>".$count."</records>";

foreach($sections as $section) 
{
    $school = School::get_by_id($section->schoolid);

    if(!$school)
    {
        $school = new School();
    }

    $batch = Batch::get_by_id($section->batchid);

    if(!$batch)
    {
        $batch = new Batch();
    }

    $s .= "<row id='". $section->id."'>";
    $s .= "<cell></cell>";          
    $s .= "<cell>". $section->id."</cell>"; 
    $s .= "<cell>". $school->id."</cell>";
    $s .= "<cell>". $school->name."</cell>";
    $s .= "<cell>". $batch->id."</cell>";
    $s .= "<cell>". $batch->get_batchyear()."</cell>";
    $s .= "<cell>". $section->name."</cell>";
    $s .= "<cell>". $section->about."</cell>";
    $s .= "<cell>". $section->picture."</cell>";
    $s .= "<cell>". $section->date."</cell>";
    $s .= "<cell>". $section->comments."</cell>";
    $s .= "<cell>". $section->pending."</cell>";
    $s .= "<cell>". $section->enabled."</cell>";
    $s .= "<cell></cell>";
    $s .= "</row>";
}

$s .= "</rows>"; 
 
echo $s;
?>