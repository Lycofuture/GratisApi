<?
header('Content-type: application/json');
// require __DIR__.'/vendor/autoload.php';
$key = $_POST['key'];
$data = json_decode($_POST['data'], true);
/*$client = OpenAI::client($key);
$result = $client->completions()->create($data);*/
require '../../need.php';
$result = need::teacher_curl('http://openai.ovooa.com/index.php', [
	'post'=>'key='.$key.'&data='.json_encode($data)
]);
need::send($result, 'text');

/*
$client = OpenAI::client('YOUR_API_KEY');

$result = $client->completions()->create([
    'model' => 'text-davinci-003',
    'prompt' => 'PHP is',
]);
*/