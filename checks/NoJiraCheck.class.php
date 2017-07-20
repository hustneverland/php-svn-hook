<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BasePreCommitCheck.class.php';

class NoJiraCheck extends BasePreCommitCheck
{
    protected $jira = array();

    function getTitle()
    {
        return "Reject no jira number";
    }

    public function renderErrorSummary()
    {
        return "No jira number like SJCGBS-XXX or KAIHUWEB-XXX";
    }

    public function checkSvnComment($comment)
    {
        $info = explode("\n", $comment);
        $hasJira = 0;
        foreach ($info as $line) {
            $line = strtoupper($line);
            $match_total = preg_match_all("/SJCGBS-\d+/", $line, $matches);
            $match_total_kaihu = preg_match_all("/KAIHUWEB-\d+/", $line, $matches_kaihu);
            if ($match_total != 0) {
                $this->jira = $matches[0];
                $hasJira = 1;
            }
            if ($match_total_kaihu != 0) {
                $this->jira = $matches_kaihu[0];
                $hasJira = 1;
            }
        }
        if ($hasJira == 0) {
            return 'No jira number like SJCGBS-XXX or KAIHUWEB-XXX';
        }
    }

    public function checkJiraComment($svnCommitedFiles)
    {
        $jira = $this->jira;
        $files = $svnCommitedFiles;
        $log = ['jira'=>$jira,'files'=>$files];
        $reqPara = json_encode($log);
        $cmd = "curl 172.20.0.124/smallTool/test.php -s -d 'para=$reqPara'";
        exec($cmd);
    }

}
