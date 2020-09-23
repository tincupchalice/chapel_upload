<?php

use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoUploadException;

$config = require(__DIR__ . '/init.php');
require $config['autoload_path'] . "autoload.php";

if (empty($config['access_token'])) {
    throw new Exception(
        'You can not upload a file without an access token. You can find this token on your app page, or generate ' .
        'one using `auth.php`.'
    );
}

// Instantiate the library with your client id, secret and access token (pulled from dev site)
$lib = new Vimeo($config['client_id'], $config['client_secret'], $config['access_token']);

$location = $config['location'];;
$fd = $config['file_delimeter'];
$ftype = $config["file_type"];
$fpath = $config['file_path'];

$_date = date_create('', timezone_open('America/Chicago'));
$vdate = date_format($_date, $config['date_format']);
$origin = $fpath . $location . $fd . $vdate . "." . $ftype;
echo $origin . "\n";
/*
// find the file based on scheduled events, cmd line args can override the schedule...
$descs = $config['descriptions'];
$ets = $config['earliest_times'];
$lts = $config['latest_times'];
for ($i = 0; $i < count($descs); ++$i)
{
    $desc = $descs[$i];
    $et= int($ets[$i]);
    $lt = int($lts[$i]);
    $elts = explode($fd, $origin);
    $time = substr($elts[-1], 0, 2);
    $date = explode($fd, $vdate)[0];
    $pattern = $date . $fd . $time;
    $time = int($time);
    $dir = @opendir($fpath);
    if ($dir)
    {
        if ($time > $et && $time < $lt)
        {
            while (($f = readdir($dir)) != FALSE)
            {
                if (strstr($f, $pattern) && strstr($f, $ftype))
                $origin = $f;
                break;
    {
            
  
}


exit(0);
*/


$description = $location . " Weekly Sermon";
$showcase_url = "https://api.vimeo.com/users/". $config['client_id'] . "/albums/". $config['showcase'] . "/videos/";
for ($i = 0; $i < count($argv); ++$i)
{
    if (!strcmp($argv[$i], "-o"))
        $origin = $argv[$i + 1];
    else if (!strcmp($argv[$i], "-l"))
        $location = $argv[$i + 1];
    else if (!strcmp($argv[$i], "-d"))
        $description = $argv[$i + 1];
}

echo 'Uploading: ' . $origin . "\n";
echo 'Location: ' . $location . "\n";
echo 'Description: ' . $description . "\n";

try {

    // Check file is done wrting
    $ret = TRUE;
    echo "Checking " . $origin . " is complete\n";
    $count = 0;
    $last_fsize = 0;
    while ($count < 10)
    {
        echo $origin . " File size:count->" . $last_fsize . ":" . $count . "\n";
        $fsize = filesize($origin);
        if ($fsize == $last_fsize)
            $count++;
        $last_fsize = $fsize;
        sleep(10);
    }


    // Upload the file and include the video title and description.
    $uri = $lib->upload($origin, array(
        'name' => $location . ' Weekly sermon',
        'description' => $description
    ));
    echo $uri . "\n";
    $elts = explode("/", trim($uri));
    $video_id = $elts[ count($elts) - 1 ];
    $c_id = $config['client_id'];
    $s_id = $config['showcase'];
    // https://api.vimeo.com/me/albums/{album_id}/videos/{video_id}
    $uri_sh = "/me" . "/albums/" . $s_id . "/videos/" . $video_id;
    echo $uri_sh . "\n";
    $uri_sh_res = $lib->request($uri_sh, array(), 'PUT');
    print_r($uri_sh_res);
    echo '\n';
    // Get the metadata response from the upload and log out the Vimeo.com url
    $video_data = $lib->request($uri . '?fields=link');
    echo '"' . $origin. ' has been uploaded to ' . $video_data['body']['link'] . "\n";
    print_r($video_data);
    echo "\n";
    // Make an API call to edit the title and description of the video.
    /*$lib->request($uri, array(
        'name' => 'Vimeo API SDK test edit',
        'description' => "This video was edited through the Vimeo API's PHP SDK.",
    ), 'PATCH');

    echo 'The title and description for ' . $uri . ' has been edited.' . "\n";
*/
    // Make an API call to see if the video is finished transcoding.
    $video_data = $lib->request($uri . '?fields=transcode.status');
    echo 'The transcode status for ' . $uri . ' is: ' . $video_data['body']['transcode']['status'] . "\n";
} catch (Exception $e) {
    // We may have had an error. We can't resolve it here necessarily, so report it to the user.
    echo 'Error uploading ' . $origin . "\n";
    echo 'Server reported: ' . $e->getMessage() . "\n";
} catch (VimeoRequestException $e) {
    echo 'There was an error making the request.' . "\n";
    echo 'Server reported: ' . $e->getMessage() . "\n";
}
