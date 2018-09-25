<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\Facades\GitHub;

class AutoIssuesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'root_user']);
    }

    public function index()
    {
        $issues = array_filter(array_map(function ($value) {
            if (strpos($value['title'], __('github.title_issue')) !== false) {
                return $value;
            }
            return null;
        }, Github::issues()->all(env('GITHUB_USER'), env('GITHUB_REPOSITORY'))));
        return view('auto_issues.index', ['issues' => $issues]);
    }

    public function show($number)
    {
        $issue = Github::issues()->show(env('GITHUB_USER'), env('GITHUB_REPOSITORY'), $number);
        $issue['body'] = sslDecrypt($this->getTrace($issue['body']));
        return view('auto_issues.show', ['issue' => $issue]);
    }

    private function getTrace($body){
        $string = " ".$body;
        $ini = strpos($string, "<backtrace>");
        if ($ini == 0) return "";
        $ini += strlen("<backtrace>");
        $len = strpos($string, "<\backtrace>", $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
