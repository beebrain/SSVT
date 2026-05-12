<?php

namespace App\Models;

use CodeIgniter\Model;

class SubmissionFileModel extends Model
{
    protected $table         = 'submission_files';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['submission_id', 'original_name', 'stored_name', 'file_path', 'file_size', 'mime_type'];
    protected $useTimestamps = false;

    public function getBySubmission(int $submissionId)
    {
        return $this->where('submission_id', $submissionId)->findAll();
    }
}
