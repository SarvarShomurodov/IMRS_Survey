<?php
// app/Console/Commands/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ClearSkillsCache extends Command
{
    protected $signature = 'cache:clear-skills';
    protected $description = 'Clear skills search cache';

    public function handle()
    {
        $redis = Redis::connection();
        $pattern = 'skills_search:*';
        
        $keys = $redis->keys($pattern);
        
        if (!empty($keys)) {
            $redis->del($keys);
            $this->info('Cleared ' . count($keys) . ' skills cache keys');
        } else {
            $this->info('No skills cache keys found');
        }
        
        return 0;
    }
}