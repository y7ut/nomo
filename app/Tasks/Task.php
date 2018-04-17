<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/4
 * Time: 20:37
 */

namespace App\Tasks;


interface Task
{
    /**
     * 更新状态
     */
     public function updateProgress();
    /**
     * 查重
     */
     public function exists();
}