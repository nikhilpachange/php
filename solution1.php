
1. The following PHP code attempts to write a log message to a file. 
However, it doesn’t work as expected and is not writing anything to the file.
 Debug and try to solve the issue.




<?php
/**
 * Function to write a message to a log file
 * 
 * @param string $message The message to write to the log file
 * 
 * @return void
 */
function writeLog($message) {
    // Open the file in append mode
    $file = fopen('log.txt', 'a');

    // Check if the file was opened successfully
    if ($file) {
        fwrite($file, $message . PHP_EOL);
        fclose($file);
    } else {
        echo "Error: Unable to open the log file.";
    }
}

writeLog('This is a test log.');
?>
