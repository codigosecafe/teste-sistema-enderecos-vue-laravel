<?php
/**
 * Created by PhpStorm.
 * User: DevMaker BackEnd
 * Date: 20/04/2018
 * Time: 17:25
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDelete
{
    use SoftDeletes;

    public function restoreIfTrashed(){
        !$this->trashed()?:$this->restore();
    }
}