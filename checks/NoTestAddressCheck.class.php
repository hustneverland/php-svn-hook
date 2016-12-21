<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BasePreCommitCheck.class.php';

class NoTestAddressCheck extends BasePreCommitCheck
{

    /*  public $extensionsToCheck = array(
        'java', 'js', 'php',
        'ini', 'xml', 'yml',
        'htm', 'html',
        'sh', 'bat'
      );*/
    public $extensionsToCheck = array(
        'css', 'js',
        'htm', 'html',
    );

    public $dirsExclude = array(
        'dev/SMS_Service/data/www/html',
        'dev/SMS_Service/testmdev'
    );


    function getTitle()
    {
        return "Reject test address in files";
    }

    public function renderErrorSummary()
    {
        return count($this->codeError) . " string 'test' found";
    }

    public function checkFileLine($file, $pos, $line)
    {
        foreach ($this->dirsExclude as $excludeDir) {
            if (strpos($file, $excludeDir) !== false) {
                return;
            }
        }
        if (!in_array($this->getExtension($file), $this->extensionsToCheck)) {
            return;
        }
        if (($pos = strpos($line, "testm")) !== false) {
            return "Char $pos is 'testm' ";
        }
    }

}
