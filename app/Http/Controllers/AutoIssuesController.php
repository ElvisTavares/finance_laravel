<?php

namespace App\Http\Controllers;
use GrahamCampbell\GitHub\Facades\GitHub;
use Crypt;

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

    public function index(){
        $issues = array_filter(array_map(function ($value) {
            if (strpos($value['title'], 'Auto inssue code: ') !== false) {
                return $value;
            }
            return null;
        }, Github::issues()->all('guifabrin', 'FinancasLaravel')));
        return view('auto_issues.index', ['issues'=> $issues]);
    }

    public function show($number){
        $issue = Github::issues()->show('guifabrin', 'FinancasLaravel', $number);
        $issue['body'] = str_replace('This is a auto inssue created by '.env('APP_URL').' for security reasons is encoded:<br> ', '', $issue['body']);
        $issue['body'] = Crypt::decrypt($issue['body']);
        return view('auto_issues.show', ['issue'=> $issue]);
    }
}
