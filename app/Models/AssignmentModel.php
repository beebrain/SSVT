<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentModel extends Model
{
    protected $table         = 'assignments';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['title', 'description', 'assignment_order', 'is_active'];
    protected $useTimestamps = false;

    public function getActive()
    {
        return $this->where('is_active', 1)->orderBy('assignment_order', 'ASC')->findAll();
    }
}
