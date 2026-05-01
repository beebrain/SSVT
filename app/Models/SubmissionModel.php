<?php

namespace App\Models;

use CodeIgniter\Model;

class SubmissionModel extends Model
{
    protected $table         = 'submissions';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['participant_id', 'assignment_id', 'note'];
    protected $useTimestamps = true;
    protected $createdField  = 'submitted_at';
    protected $updatedField  = 'updated_at';

    public function getByParticipantAndAssignment(int $participantId, int $assignmentId)
    {
        return $this->where('participant_id', $participantId)
                    ->where('assignment_id', $assignmentId)
                    ->first();
    }

    public function getSubmissionMatrix()
    {
        $db = \Config\Database::connect();

        $participants = $db->table('participants')->orderBy('name', 'ASC')->get()->getResultArray();
        $assignments  = $db->table('assignments')->where('is_active', 1)->orderBy('assignment_order', 'ASC')->get()->getResultArray();

        $submittedIds = [];
        $submissions  = $db->table('submissions')->get()->getResultArray();
        foreach ($submissions as $s) {
            $submittedIds[$s['participant_id']][$s['assignment_id']] = $s['id'];
        }

        return [
            'participants'  => $participants,
            'assignments'   => $assignments,
            'submittedIds'  => $submittedIds,
        ];
    }

    public function getWithFiles(int $submissionId)
    {
        $db = \Config\Database::connect();
        $submission = $db->table('submissions s')
            ->select('s.*, p.name as participant_name, a.title as assignment_title')
            ->join('participants p', 'p.id = s.participant_id')
            ->join('assignments a', 'a.id = s.assignment_id')
            ->where('s.id', $submissionId)
            ->get()->getRowArray();

        if ($submission) {
            $submission['files'] = $db->table('submission_files')
                ->where('submission_id', $submissionId)
                ->get()->getResultArray();
        }

        return $submission;
    }
}
