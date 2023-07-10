<?
header('content-type: application/json');
require './config.php';
if($result = $db->query("select access, status from `mxgapi_api`")->fetch_all(1))
{
	$o = 0;
	$s = 0;
	foreach($result as $v)
	{
		if(is_numeric($v['access']) && $v['status'] == 1)
		{
			$o += $v['access'];
			$s++;
		}
	}
	echo "数量：{$s}个\n";
	echo "总共调用{$o}次";
} else {
	echo '木有';
}