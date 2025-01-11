<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$role = Role::create(['name' => 'admin']);
$permission = Permission::create(['name' => 'edit articles']);
