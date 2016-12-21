<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BasePreCommitCheck.class.php';

class NoReviewCheck extends BasePreCommitCheck
{

    function getTitle()
    {
        return "Reject not reviewed";
    }

    public function renderErrorSummary()
    {
        return "Not reviewed";
    }

    public function checkSvnComment($comment)
    {
        $info = explode("\n", $comment);
        $reviewed = 0;
        foreach ($info as $line) {
            $lineInfo = explode(":", $line);
            $title = strtolower(trim($lineInfo[0]));
            $value = trim($lineInfo[1]);
            if (strpos($title, 'review') !== false) {
                if ($value != "") {
                    $reviewed = 1;
                    break;
                }
            }
        }
        if ($reviewed == 0) {
            return "you have not reviewed!";
        }
    }

}
