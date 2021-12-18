<?php

// get the shell arguments
$argv = $_SERVER['argv'];
$pattern = "/GX[0-9]{6}.MP4/";
$dir = $argv[1] ?? './';
// make sure the directory has a trailing slash
if (substr($dir, -1) != '/') {
    $dir .= '/';
}

$d = dir($dir) or die();

while (false !== ($f = $d->read())) {
    if (preg_match($pattern, $f)) {
        $tmp[] = $f;
    }
}

$d->close();

foreach ($tmp as $arg) {
    $episode = substr($arg, 4, 4);
    $chapter = substr($arg, 2, 2);
    // if any $chapter starts with a 0, remove it
    if (substr($chapter, 0, 1) == '0') {
        $chapter = substr($chapter, 1, 1);
    }
    
    $files[$episode][$chapter] = $arg;
}
foreach ($files as $episode => $chapters) {
    ksort($files[$episode]);
}

// sort by episode
ksort($files);

$episodeListsDir = $argv[3] ?? $dir.'episode-lists/';
// make sure the directory has a trailing slash
if (substr($episodeListsDir, -1) != '/') {
    $episodeListsDir .= '/';
}
// replace spaces in episodes dir name with 2 backslashes
$episodesDir = str_replace(' ', '\\ ', $dir);
// make the directory if it doesn't exist
if (!file_exists($episodeListsDir)) {
    mkdir($episodeListsDir);
}

// foreach episode output a file with the chapter file names
foreach ($files as $episode => $chapters) {
    $file = fopen($episodeListsDir."$episode.txt", "w");
    foreach ($chapters as $chapter => $fileName) {
        // write the file encoded in UTF-8
        $fileContents = "file '../".$fileName."'\n";
        fwrite($file, $fileContents);
    }
    fclose($file);
}

// make a var for the output files directory
$outputDir = $argv[2] ?? $dir.'output/';
// make sure the directory has a trailing slash
if (substr($outputDir, -1) != '/') {
    $outputDir .= '/';
}
// make the directory if it doesn't exist
if (!file_exists($outputDir)) {
    mkdir($outputDir);
}

// run ffmpeg on each file in the episode list by passing the chapter list as the input
foreach ($files as $episode => $chapters) {
    // escape the output file
    $outputFile = $outputDir."$episode.MP4";
    $outputFileCheck = $outputFile;
    $outputFile = str_replace(' ', '\\ ', $outputFile);
    // replace spaces with 2 backslashes in the episode list
    $chapterList = $episodeListsDir."$episode.txt";
    $chapterListRaw = $chapterList;
    $chapterList = str_replace(' ', '\\ ', $chapterList);
    $cmd = "ffmpeg -y -f concat -safe 0 -i $chapterList -copy_unknown -map_metadata 0 \\
        -c copy \\
        -map 0:v -map 0:a \\
        -map 0:d \\
        -map 0:d\? \\
        -map 0:m:handler_name:' GoPro SOS'\? \\
        -tag:d:1 'gpmd' -tag:d:2 'gpmd' \\
        -metadata:s:v: handler='        GoPro AVC' \\
        -metadata:s:a: handler='        GoPro AAC' \\
        -metadata:s:d:0 handler='       GoPro TCD' \\
        -metadata:s:d:1 handler='       GoPro MET' \\
        -metadata:s:d:2 handler='       GoPro SOS (original fdsc stream)' \\
        $outputFile";
    // check if the output file exists
    if (file_exists($outputFileCheck)) {
        echo "Output file $outputFileCheck exists, skipping\n";
    } else {
        // output the contents of the $chapterList file
        $chapterListOutput = fopen($chapterListRaw, "r");
        $chapterListOutputContents = fread($chapterListOutput, filesize($chapterListRaw));
        fclose($chapterListOutput);
        echo "Output file $outputFileCheck does not exist\n";
        echo "Running ffmpeg on episode: #$episode \n";
        echo "Using these files:\n";
        echo $chapterListOutputContents;
        echo ">>\n";
        echo ">>\n";
        echo ">>\n";
        echo $cmd."\n";
        exec($cmd);
    }
    // use touch to set the file modification time to the episode's first chapter
    $firstChapter = $chapters[1];
    $firstChapter = $episodesDir.$firstChapter;
    $cmd = "touch -r $firstChapter $outputFile";
    echo $cmd."\n";
    echo "\n";
    echo "\n";
    echo "\n";
    exec($cmd);
}
