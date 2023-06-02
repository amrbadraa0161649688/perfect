<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Branch;


class getTransCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trans:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check new transactions and store it';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info('trans cron started');
        $fromDate = Carbon::now()->subDays(1)->format('m/d/Y');//'02/06/2023';
        $toDate = Carbon::now()->format('m/d/Y');//'02/07/2023';
        $branches = Branch::where('station_id','!=',null)->get();

        $res = collect();
        foreach($branches as $branch)
        {
            ini_set('max_execution_time', 180); 
            $res->add([ 
                'station' => $branch->station_id,
                'msg'=> \App\Http\Controllers\Qserv\QservAPIController::GetTransactions($fromDate,$toDate,$branch->station_id)['msg'],
            ]);

        }
        info($res);
        info('trans cron finished');
        
        return 0;

    }
}
