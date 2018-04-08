<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/4
 * Time: 20:38
 */

namespace App\Tasks;


use App\User;

abstract class DailyTask implements Task
{
    protected $redis;
    protected $user;
    protected $state=0;
    protected $progress_size;
    protected $task_content;
    protected $progress_number=0;

    public function __construct()
    {
        $this->redis = app('redis.connection');
    }


    abstract public function updateProgress();
    abstract public function exists();

}