<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    
    protected $table = 'apps';
    protected $fillable = ['id', 'name', 'alias', 'prefix', 'app_description', 'public', 'security', 'token', 'structure', 'active', 'users'];

}
