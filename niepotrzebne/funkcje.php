<?php
/* Another little handy tool; to get the most recent modified time from files in a directory. It even does recursive directories if you set the $doRecursive param to true. Based on a file/directory list function I saw somewhere on this site. ;) */
function mostRecentModifiedFileTime($dirName,$doRecursive) {
    $d = dir($dirName);
    $lastModified = 0;
    while($entry = $d->read()) {
        if ($entry != "." && $entry != "..") {
            if (!is_dir($dirName."/".$entry)) {
                $currentModified = filemtime($dirName."/".$entry);
            } else if ($doRecursive && is_dir($dirName."/".$entry)) {
                $currentModified = mostRecentModifiedFileTime($dirName."/".$entry,true);
            }
            if ($currentModified > $lastModified){
                $lastModified = $currentModified;
            }
        }
    }
    $d->close();
    return $lastModified;
} 

/* i needed the ability to grab the mod time of an image on a remote site. the following is the solution with the help of Joe Ferris. */
function filemtime_remote($uri)
{
    $uri = parse_url($uri);
    $handle = @fsockopen($uri['host'],80);
    if(!$handle)
        return 0;

    fputs($handle,"GET $uri[path] HTTP/1.1\r\nHost: $uri[host]\r\n\r\n");
    $result = 0;
    while(!feof($handle))
    {
        $line = fgets($handle,1024);
        if(!trim($line))
            break;

        $col = strpos($line,':');
        if($col !== false)
        {
            $header = trim(substr($line,0,$col));
            $value = trim(substr($line,$col+1));
            if(strtolower($header) == 'last-modified')
            {
                $result = strtotime($value);
                break;
            }
        }
    }
    fclose($handle);
    return $result;
}
// echo filemtime_remote('http://www.somesite.com/someimage.jpg');

?>