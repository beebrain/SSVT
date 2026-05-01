<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipantModel extends Model
{
    protected $table            = 'participants';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'participant_code'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function findByCode(string $code)
    {
        return $this->where('participant_code', $code)->first();
    }

    public function getWithSubmissionSummary()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('participants p');
        $builder->select('p.*');

        // Get assignment count
        $assignments = $db->table('assignments')->where('is_active', 1)->countAllResults();

        $builder->orderBy('p.name', 'ASC');
        $participants = $builder->get()->getResultArray();

        // Get submissions count per participant
        foreach ($participants as &$p) {
            $submitted = $db->table('submissions')
                ->where('participant_id', $p['id'])
                ->countAllResults();
            $p['submitted_count'] = $submitted;
            $p['total_assignments'] = $assignments;
        }

        return $participants;
    }
}
