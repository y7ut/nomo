<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/4
 * Time: 20:52
 */

namespace App\Tasks;


abstract class AdminTask implements Task
{
    protected $redis;
    protected $user;
    protected $state=0;
    protected $task_content;


    public function __construct()
    {
        $this->redis = app('redis.connection');
    }

}