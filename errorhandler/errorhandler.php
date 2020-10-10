<?php
/**
 * This where you can set if you want to put the error in the database or show it
 */
function customError($errno, $errstr, $errfile, $errline) {
    echo "<b>Custom error:</b> [$errno] $errstr<br>";
}

/**
 * for try-catch
 * public function getMessage();                 // Exception message
 *  public function getCode();                    // User-defined Exception code
 *   public function getFile();                    // Source filename
 *   public function getLine();                    // Source line
 *   public function getTrace();                   // An array of the backtrace()
 *   public function getTraceAsString();           // Formated string of trace  
 * This where you can set if you want to put the error in the database or return it
 */
class customException extends Exception {
    public function errorMessage() {
      //error message
      $errorMsg = $this->getMessage();
      return $errorMsg;
    }
    public function errorCode() {
      //error code
      $errorCode = $this->getCode();
      return $errorCode;
    }
  }
 

?>