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
$error_email = $config['error_email'];

$fu_path = $config['file_upload_path'];
if (!is_dir($fu_path))
{
    if (!mkdir($fu_path, 0775))
    {
        $subject = "Could not make upload directory " . $fu_path;
        mail($error_email, $subject, $subject);
        throw new Exception("Unable to make upload directory, please check permissions");
    }
}
$fa_path = $config['file_archive_path'];
if (!is_dir($fa_path))
{
    if (!mkdir($fa_path, 0775))
    {
        $subject = "Could not make archive directory " . $fa_path;
        mail($error_email, $subject, $subject);
        throw new Exception("Unable to make archive directory, please check permissions");
    }
}
$fs_path = $config['file_small_path'];
if (!is_dir($fs_path))
{
    if (!mkdir($fs_path, 0775))
    {
        $subject = "Could not make small directory " . $fs_path;
        mail($error_email, $subject, $subject);
        throw new Exception("Unable to make small directory, please check permissions");
    }
}

$manual = false;
$min_file_size = $config["min_file_size"];
$description = NULL;

for ($i = 0; $i < count($argv); ++$i)
{
    if (!strcmp($argv[$i], "-o"))
    {
        $origin = $argv[$i + 1];
        $manual = TRUE;
    }
    else if (!strcmp($argv[$i], "-l"))
        $location = $argv[$i + 1];
    else if (!strcmp($argv[$i], "-d"))
        $description = $argv[$i + 1];
}
$file_list = array();
if ($manual)
{
    $file_list[0] = $origin;
    echo $origin . "\n";
    if (!$description)
        $description = $explode(".", $origin)[0];
}
else
{
    echo $fu_path . "\n";
    while (true)
    {
        $dh = @opendir($fu_path);
        if ($dh)
        {
            $i = 0;
            while (($fname = readdir($dh)) !== false) {
                echo $fname . "\n";
                if (!strcmp(".", $fname) || !strcmp("..", $fname))
                    continue;
                $file_list[$i++] = $fu_path . $fname;
            }
            if (count($file_list))
                break;
            else
            {
                echo "Waiting on file, none avialable\n";
                sleep(5);
            }
        }
    }
}

for( $i = 0; $i < count($file_list); $i++)
{
    $origin = $file_list[$i];
    echo $origin . "\n";
    if (!$description)
        $description = explode(".", $origin)[0];
    try 
    {
        // Check file is done wrting
        $ret = TRUE;
        echo "Checking " . $origin . " is complete\n";
        $count = 0;
        $last_fsize = 0;
        while ($count < 10) 
        {
            $fsize = filesize($origin);
            echo $origin . " File size:count->" . $fsize . ":" . $last_fsize . ":" . $count . "\n";
            if ($fsize == $last_fsize)
            {
                $count++;
            }
            else
                $count = 0;
            $last_fsize = $fsize;
            sleep(1);
        }
        if ($fsize < $min_file_size)
        {
            // move file to small folder, not a valid video
            $base = basename($origin, "." . $ftype);
            rename($origin, $fs_path . $base);
            // email error
            $subject = "File too small.  Moving " . $base . " to " . $fs_path;
            mail($error_email, $subject, "Recording may have not finished");      
        }
        else
        {
            // upload file to vimeo
            // error - email error
            // move file to archive folder
            // email success
        }
        
        {
            echo 'Uploading: ' . $origin . "\n";
            echo 'Location: ' . $location . "\n";
            echo 'Description: ' . $description . "\n";        
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
        // Make an API call to see if the video is finished transcoding.
        $video_data = $lib->request($uri . '?fields=transcode.status');
        echo 'The transcode status for ' . $uri . ' is: ' . $video_data['body']['transcode']['status'] . "\n";
    } 
    catch (VimeoRequestException $e) 
    {
        echo 'There was an error making the request.' . "\n";
        echo 'Server reported: ' . $e->getMessage() . "\n";
    }
    catch (Exception $e) 
    {
        // We may have had an error. We can't resolve it here necessarily, so report it to the user.
        echo 'Error uploading ' . $origin . "\n";
        echo 'Server reported: ' . $e->getMessage() . "\n";
    }
} 
