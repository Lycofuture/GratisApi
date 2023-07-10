<?php


//require_once './function.php';
class LCS
{
	var $str1;
	var $str2;
	var $c = array();
 
	/*返回串一和串二的最长公共子序列
	*/
	function getLCS($str1, $str2, $len1 = 0, $len2 = 0)
	{
		$this->str1 = $str1;
		$this->str2 = $str2;
		if ($len1 == 0) $len1 = strlen($str1);
		if ($len2 == 0) $len2 = strlen($str2);
		$this->initC($len1, $len2);
		return $this->printLCS($this->c, $len1 - 1, $len2 - 1);
	}
 
	/*返回两个串的相似度
	*/
	function getSimilar($str1, $str2)
	{
		$len1 = strlen($str1);
		$len2 = strlen($str2);
		$len = strlen($this->getLCS($str1, $str2, $len1, $len2));
		return $len * 2 / ($len1 + $len2);
	}
 
	function initC($len1, $len2)
	{
		for ($i = 0; $i < $len1; $i++) $this->c[$i][0] = 0;
		for ($j = 0; $j < $len2; $j++) $this->c[0][$j] = 0;
		for ($i = 1; $i < $len1; $i++) {
			for ($j = 1; $j < $len2; $j++) {
				if ($this->str1[$i] == $this->str2[$j]) {
					$this->c[$i][$j] = $this->c[$i - 1][$j - 1] + 1;
				} else if ($this->c[$i - 1][$j] >= $this->c[$i][$j - 1]) {
					$this->c[$i][$j] = $this->c[$i - 1][$j];
				} else {
					$this->c[$i][$j] = $this->c[$i][$j - 1];
				}
			}
		}
	}
 
	function printLCS($c, $i, $j)
	{
		if ($i == 0 || $j == 0) {
			if ($this->str1[$i] == $this->str2[$j]) return $this->str2[$j];
			else return "";
		}
		if ($this->str1[$i] == $this->str2[$j]) {
			return $this->printLCS($this->c, $i - 1, $j - 1) . $this->str2[$j];
		} else if ($this->c[$i - 1][$j] >= $this->c[$i][$j - 1]) {
			return $this->printLCS($this->c, $i - 1, $j);
		} else {
			return $this->printLCS($this->c, $i, $j - 1);
		}
	}
}
 
$lcs = new LCS();
//返回最长公共子序列
echo $lcs->getLCS("123456789", "987654321");
echo '<br/>';
//返回相似度
echo '自定义函数的出来的：'. $lcs->getSimilar("123456789", "987654321");
echo '<br/>';
echo 'similar_text 得出的匹配个数： '.similar_text("123456789","987654321");
echo '<br/>';
echo '字符串本来的长度'.(strlen('123456789'));
echo '<br/>';
echo '待对比字符串本来的长度'.(strlen('987654321'));
echo '<br/>';
similar_text("123456789","987654321",$p);
echo 'similar_text 得出的匹配百分比： '.$p;
