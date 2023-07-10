<?

$file = file(__DIR__.'/Cy_cache.txt');
foreach(array_unique($file) as $v){
	file_put_contents(__DIR__.'/1.txt', $v, FILE_APPEND);
}
echo '好';