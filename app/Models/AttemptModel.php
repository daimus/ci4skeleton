<?php

namespace App\Models;

use CodeIgniter\Model;

class AttemptModel extends Model
{
    protected $table      = 'attempts';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['user_id', 'ip_address', 'user_agent', 'geolocation', 'error', 'message', 'ignored'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
