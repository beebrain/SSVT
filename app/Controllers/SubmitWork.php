<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\AssignmentModel;
use App\Models\SubmissionModel;
use App\Models\SubmissionFileModel;

class SubmitWork extends BaseController
{
    protected $participantModel;
    protected $assignmentModel;
    protected $submissionModel;
    protected $fileModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->participantModel = new ParticipantModel();
        $this->assignmentModel  = new AssignmentModel();
        $this->submissionModel  = new SubmissionModel();
        $this->fileModel        = new SubmissionFileModel();
    }

    private function getParticipant()
    {
        $session = session();
        $id = $session->get('participant_id');
        if (!$id) return null;
        return $this->participantModel->find($id);
    }

    public function login()
    {
        if ($this->getParticipant()) {
            return redirect()->to(base_url('my-work'));
        }
        return view('participant/login', ['title' => 'เข้าสู่ระบบ']);
    }

    public function doLogin()
    {
        $code = trim($this->request->getPost('participant_code'));
        $participant = $this->participantModel->findByCode($code);

        if (!$participant) {
            return view('participant/login', [
                'title' => 'เข้าสู่ระบบ',
                'error' => 'ไม่พบรหัสผู้เข้าอบรม กรุณาตรวจสอบอีกครั้ง',
            ]);
        }

        session()->set([
            'participant_id'   => $participant['id'],
            'participant_name' => $participant['name'],
            'participant_code' => $participant['participant_code'],
        ]);

        return redirect()->to(base_url('my-work'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }

    public function myWork()
    {
        $participant = $this->getParticipant();
        if (!$participant) {
            return redirect()->to(base_url('login'));
        }

        $assignments = $this->assignmentModel->getActive();
        $db = \Config\Database::connect();

        foreach ($assignments as &$a) {
            $sub = $this->submissionModel->getByParticipantAndAssignment($participant['id'], $a['id']);
            $a['submitted']    = !empty($sub);
            $a['submission_id'] = $sub['id'] ?? null;
            if ($sub) {
                $a['file_count'] = $db->table('submission_files')->where('submission_id', $sub['id'])->countAllResults();
            }
        }

        return view('participant/my_work', [
            'title'        => 'ผลงานของฉัน',
            'participant'  => $participant,
            'assignments'  => $assignments,
        ]);
    }

    public function submit(int $assignmentId)
    {
        $participant = $this->getParticipant();
        if (!$participant) {
            return redirect()->to(base_url('login'));
        }

        $assignment = $this->assignmentModel->find($assignmentId);
        if (!$assignment || !$assignment['is_active']) {
            return redirect()->to(base_url('my-work'))->with('error', 'ไม่พบผลงานที่ต้องการ');
        }

        $submission = $this->submissionModel->getByParticipantAndAssignment($participant['id'], $assignmentId);
        $files = $submission ? $this->fileModel->getBySubmission($submission['id']) : [];

        return view('participant/submit', [
            'title'       => 'ส่ง' . $assignment['title'],
            'participant' => $participant,
            'assignment'  => $assignment,
            'submission'  => $submission,
            'files'       => $files,
        ]);
    }

    public function doSubmit(int $assignmentId)
    {
        $participant = $this->getParticipant();
        if (!$participant) {
            return redirect()->to(base_url('login'));
        }

        $assignment = $this->assignmentModel->find($assignmentId);
        if (!$assignment) {
            return redirect()->to(base_url('my-work'))->with('error', 'ไม่พบผลงาน');
        }

        // Get or create submission
        $submission = $this->submissionModel->getByParticipantAndAssignment($participant['id'], $assignmentId);
        $note = trim($this->request->getPost('note') ?? '');

        if (!$submission) {
            $subId = $this->submissionModel->insert([
                'participant_id' => $participant['id'],
                'assignment_id'  => $assignmentId,
                'note'           => $note,
            ]);
            $submissionId = $subId;
        } else {
            $this->submissionModel->update($submission['id'], ['note' => $note]);
            $submissionId = $submission['id'];
        }

        // Handle file uploads
        $uploadPath = WRITEPATH . 'uploads/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

        // Use getFileMultiple so `name="images[]"` always yields an array (single upload via getFiles() can be one object, not iterable as files).
        $imageFiles   = $this->request->getFileMultiple('images');
        $uploadErrors = [];

        if (!empty($imageFiles)) {
            foreach ($imageFiles as $file) {
                if (!$file->isValid() || $file->hasMoved()) continue;
                if ($file->getError() === UPLOAD_ERR_NO_FILE) continue;

                // MIME/size must be read before move(); temp file is gone after move() and getMimeType() uses finfo on that path.
                $mimeType = $file->getMimeType();
                $byteSize = $file->getSize();

                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/heic', 'image/heif'];
                if (!in_array($mimeType, $allowed)) {
                    $uploadErrors[] = $file->getName() . ': ประเภทไฟล์ไม่รองรับ';
                    continue;
                }

                if ($byteSize > 10 * 1024 * 1024) {
                    $uploadErrors[] = $file->getName() . ': ไฟล์ใหญ่เกิน 10MB';
                    continue;
                }

                $newName = $file->getRandomName();
                $originalName = $file->getClientName();
                $file->move($uploadPath, $newName);

                $this->fileModel->insert([
                    'submission_id' => $submissionId,
                    'original_name' => $originalName,
                    'stored_name'   => $newName,
                    'file_path'     => 'uploads/' . $newName,
                    'file_size'     => $byteSize,
                    'mime_type'     => $mimeType,
                ]);
            }
        }

        if (!empty($uploadErrors)) {
            return redirect()->to(base_url('submit/' . $assignmentId))->with('error', implode('<br>', $uploadErrors));
        }

        return redirect()->to(base_url('submit/' . $assignmentId))->with('success', 'บันทึกผลงานเรียบร้อยแล้ว');
    }

    public function deleteFile(int $assignmentId, int $fileId)
    {
        $participant = $this->getParticipant();
        if (!$participant) {
            return redirect()->to(base_url('login'));
        }

        $file = $this->fileModel->find($fileId);
        if ($file) {
            // Verify ownership
            $submission = $this->submissionModel->find($file['submission_id']);
            if ($submission && $submission['participant_id'] == $participant['id']) {
                $path = WRITEPATH . 'uploads/' . $file['stored_name'];
                if (file_exists($path)) unlink($path);
                $this->fileModel->delete($fileId);
            }
        }

        return redirect()->to(base_url('submit/' . $assignmentId))->with('success', 'ลบไฟล์เรียบร้อยแล้ว');
    }
}
