<?php

// Repair script for corrupted GoPro MP4 files
// Usage: php repair.php /path/to/gopro/files [/path/to/output/dir] [specific_file.MP4]

// get the shell arguments
$argv = $_SERVER['argv'];
$pattern = "/GX[0-9]{6}.MP4/";
$dir = $argv[1] ?? './';

// make sure the directory has a trailing slash
if (substr($dir, -1) != '/') {
    $dir .= '/';
}

// check if a specific file is provided
$specificFile = $argv[3] ?? null;

// make a var for the output files directory
$outputDir = $argv[2] ?? $dir.'repaired/';
// make sure the directory has a trailing slash
if (substr($outputDir, -1) != '/') {
    $outputDir .= '/';
}
// make the directory if it doesn't exist
if (!file_exists($outputDir)) {
    mkdir($outputDir);
}

$files = [];

// if specific file is provided, only process that file
if ($specificFile) {
    if (preg_match($pattern, $specificFile)) {
        $files[] = $specificFile;
    } else {
        die("Error: File does not match GoPro naming pattern (GXCCEEEE.MP4)\n");
    }
} else {
    // scan directory for all GoPro files
    $d = dir($dir) or die("Error: Cannot open directory\n");
    
    while (false !== ($f = $d->read())) {
        if (preg_match($pattern, $f)) {
            $files[] = $f;
        }
    }
    
    $d->close();
}

if (empty($files)) {
    die("No GoPro files found to repair\n");
}

// sort files
sort($files);

echo "Found ".count($files)." file(s) to repair\n\n";

// replace spaces in dir name with 2 backslashes for shell commands
$inputDir = str_replace(' ', '\\ ', $dir);
$outputDirEscaped = str_replace(' ', '\\ ', $outputDir);

// repair each file
foreach ($files as $fileName) {
    $inputFile = $inputDir.$fileName;
    $outputFile = $outputDirEscaped.$fileName;
    $outputFileCheck = $outputDir.$fileName;
    
    // check if the output file already exists
    if (file_exists($outputFileCheck)) {
        echo "Repaired file $outputFileCheck already exists, skipping\n";
        continue;
    }
    
    echo "Repairing: $fileName\n";
    
    // ffmpeg command to repair the file
    // This uses -c copy to copy streams without re-encoding, which is fast
    // and preserves quality while fixing container issues
    $cmd = "ffmpeg -y -err_detect ignore_err -i $inputFile \\
        -c copy \\
        -map 0:v? -map 0:a? \\
        -map 0:d? \\
        -metadata:s:v: handler='        GoPro AVC' \\
        -metadata:s:a: handler='        GoPro AAC' \\
        $outputFile";
    
    echo "Running: $cmd\n";
    exec($cmd, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "Successfully repaired: $fileName\n";
        
        // use touch to preserve the original file modification time
        $touchCmd = "touch -r $inputFile $outputFile";
        exec($touchCmd);
    } else {
        echo "Error repairing: $fileName (exit code: $returnCode)\n";
    }
    
    echo "\n";
}

echo "Repair process completed\n";
