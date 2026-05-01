<?php

namespace App\Controllers;

use App\Models\ParticipantModel;
use App\Models\AssignmentModel;
use App\Models\SubmissionModel;
use App\Models\SubmissionFileModel;
use App\Models\WordCloudModel;
use CodeIgniter\Controller;

class Admin extends BaseController
{
    protected $participantModel;
    protected $assignmentModel;
    protected $submissionModel;
    protected $fileModel;
    protected $wordCloudModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->participantModel = new ParticipantModel();
        $this->assignmentModel  = new AssignmentModel();
        $this->submissionModel  = new SubmissionModel();
        $this->fileModel        = new SubmissionFileModel();
        $this->wordCloudModel   = new WordCloudModel();
    }

    // ======================== ADMIN AUTH ========================

    public function loginPage()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return view('admin/login', ['title' => 'เข้าสู่ระบบผู้ดูแล']);
    }

    public function doLogin()
    {
        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        // Simple hardcoded credentials — change as needed
        $validUser = env('ADMIN_USER', 'admin');
        $validPass = env('ADMIN_PASS', 'admin1234');

        if ($username === $validUser && $password === $validPass) {
            session()->set('admin_logged_in', true);
            session()->set('admin_username', $username);
            return redirect()->to(base_url('admin/dashboard'));
        }

        return view('admin/login', [
            'title' => 'เข้าสู่ระบบผู้ดูแล',
            'error' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }

    public function doLogout()
    {
        session()->remove(['admin_logged_in', 'admin_username']);
        return redirect()->to(base_url('admin/login'));
    }

    // ======================== DASHBOARD ========================

    public function dashboard()
    {
        $db = \Config\Database::connect();
        $data = [
            'title'             => 'แดชบอร์ด',
            'totalParticipants' => $this->participantModel->countAll(),
            'totalAssignments'  => $this->assignmentModel->where('is_active', 1)->countAll(),
            'totalSubmissions'  => $this->submissionModel->countAll(),
            'totalWordcloud'    => $this->wordCloudModel->getTotalEntries(),
        ];

        // Submission completion per assignment
        $assignments = $this->assignmentModel->getActive();
        $totalP      = $this->participantModel->countAll();
        foreach ($assignments as &$a) {
            $a['submitted'] = $db->table('submissions')->where('assignment_id', $a['id'])->countAllResults();
            $a['percent']   = $totalP > 0 ? round($a['submitted'] / $totalP * 100) : 0;
        }
        $data['assignments'] = $assignments;
        $data['totalP']      = $totalP;

        return view('admin/layout', $data + ['content' => view('admin/dashboard', $data)]);
    }

    // ======================== PARTICIPANTS ========================

    public function participants()
    {
        $data = [
            'title'        => 'จัดการผู้เข้าอบรม',
            'participants' => $this->participantModel->getWithSubmissionSummary(),
        ];
        return view('admin/layout', $data + ['content' => view('admin/participants', $data)]);
    }

    public function addParticipant()
    {
        $name = trim($this->request->getPost('name'));
        $code = trim($this->request->getPost('participant_code'));

        if (empty($name) || empty($code)) {
            return redirect()->to(base_url('admin/participants'))->with('error', 'กรุณากรอกข้อมูลให้ครบ');
        }

        // Check duplicate code
        if ($this->participantModel->findByCode($code)) {
            return redirect()->to(base_url('admin/participants'))->with('error', 'รหัสผู้เข้าอบรมซ้ำ: ' . $code);
        }

        $this->participantModel->insert(['name' => $name, 'participant_code' => $code]);
        return redirect()->to(base_url('admin/participants'))->with('success', 'เพิ่มผู้เข้าอบรมเรียบร้อย');
    }

    public function editParticipant(int $id)
    {
        $name = trim($this->request->getPost('name'));
        $code = trim($this->request->getPost('participant_code'));

        if (empty($name) || empty($code)) {
            return redirect()->to(base_url('admin/participants'))->with('error', 'กรุณากรอกข้อมูลให้ครบ');
        }

        // Check duplicate code (exclude self)
        $existing = $this->participantModel->where('participant_code', $code)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->to(base_url('admin/participants'))->with('error', 'รหัสผู้เข้าอบรมซ้ำ: ' . $code);
        }

        $this->participantModel->update($id, ['name' => $name, 'participant_code' => $code]);
        return redirect()->to(base_url('admin/participants'))->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function deleteParticipant(int $id)
    {
        $this->participantModel->delete($id);
        return redirect()->to(base_url('admin/participants'))->with('success', 'ลบผู้เข้าอบรมเรียบร้อย');
    }

    public function importParticipants()
    {
        $file = $this->request->getFile('csv_file');

        if (!$file || !$file->isValid()) {
            return redirect()->to(base_url('admin/participants'))->with('error', 'กรุณาเลือกไฟล์ CSV');
        }

        $content  = file_get_contents($file->getTempName());
        $content  = mb_convert_encoding($content, 'UTF-8', 'UTF-8,TIS-620,Windows-874');
        $lines    = explode("\n", str_replace("\r\n", "\n", $content));
        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($lines as $i => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $cols = str_getcsv($line);
            if (count($cols) < 2) continue;

            $name = trim($cols[0]);
            $code = trim($cols[1]);

            if (empty($name) || empty($code)) continue;

            // Skip header row
            if ($i === 0 && (strtolower($name) === 'name' || strtolower($name) === 'ชื่อ')) continue;

            if ($this->participantModel->findByCode($code)) {
                $skipped++;
                continue;
            }

            $this->participantModel->insert(['name' => $name, 'participant_code' => $code]);
            $imported++;
        }

        $msg = "นำเข้าสำเร็จ {$imported} รายการ";
        if ($skipped > 0) $msg .= ", ข้ามรหัสซ้ำ {$skipped} รายการ";

        return redirect()->to(base_url('admin/participants'))->with('success', $msg);
    }

    public function exportParticipants()
    {
        $participants = $this->participantModel->orderBy('name')->findAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="participants.csv"');
        echo "\xEF\xBB\xBF"; // BOM for UTF-8

        $out = fopen('php://output', 'w');
        fputcsv($out, ['ชื่อ', 'รหัส']);
        foreach ($participants as $p) {
            fputcsv($out, [$p['name'], $p['participant_code']]);
        }
        fclose($out);
        exit;
    }

    // ======================== ASSIGNMENTS ========================

    public function assignments()
    {
        $data = [
            'title'       => 'จัดการผลงาน',
            'assignments' => $this->assignmentModel->orderBy('assignment_order')->findAll(),
        ];
        return view('admin/layout', $data + ['content' => view('admin/assignments', $data)]);
    }

    public function saveAssignment()
    {
        $this->assignmentModel->insert([
            'title'            => trim($this->request->getPost('title')),
            'description'      => trim($this->request->getPost('description')),
            'assignment_order' => (int)$this->request->getPost('assignment_order'),
            'is_active'        => 1,
        ]);
        return redirect()->to(base_url('admin/assignments'))->with('success', 'เพิ่มผลงานเรียบร้อย');
    }

    public function editAssignment(int $id)
    {
        $this->assignmentModel->update($id, [
            'title'            => trim($this->request->getPost('title')),
            'description'      => trim($this->request->getPost('description')),
            'assignment_order' => (int)$this->request->getPost('assignment_order'),
            'is_active'        => (int)$this->request->getPost('is_active'),
        ]);
        return redirect()->to(base_url('admin/assignments'))->with('success', 'แก้ไขผลงานเรียบร้อย');
    }

    public function deleteAssignment(int $id)
    {
        $this->assignmentModel->delete($id);
        return redirect()->to(base_url('admin/assignments'))->with('success', 'ลบผลงานเรียบร้อย');
    }

    // ======================== SUBMISSIONS ========================

    public function submissions()
    {
        $matrix = $this->submissionModel->getSubmissionMatrix();
        $data = [
            'title'  => 'ภาพรวมการส่งผลงาน',
            'matrix' => $matrix,
        ];
        return view('admin/layout', $data + ['content' => view('admin/submissions', $data)]);
    }

    public function submissionFiles(int $submissionId)
    {
        $submission = $this->submissionModel->getWithFiles($submissionId);
        if (!$submission) {
            return redirect()->to(base_url('admin/submissions'))->with('error', 'ไม่พบข้อมูลการส่งงาน');
        }
        $data = [
            'title'      => 'ไฟล์ที่ส่ง',
            'submission' => $submission,
        ];
        return view('admin/layout', $data + ['content' => view('admin/submission_files', $data)]);
    }

    public function deleteFile(int $fileId)
    {
        $file = $this->fileModel->find($fileId);
        if ($file) {
            $path = WRITEPATH . 'uploads/' . $file['stored_name'];
            if (file_exists($path)) unlink($path);
            $this->fileModel->delete($fileId);
        }
        return redirect()->back()->with('success', 'ลบไฟล์เรียบร้อย');
    }

    // ======================== WORDCLOUD ========================

    public function wordcloud()
    {
        $data = [
            'title'     => 'WordCloud',
            'words'     => $this->wordCloudModel->getWordFrequency(),
            'total'     => $this->wordCloudModel->getTotalEntries(),
        ];
        return view('admin/layout', $data + ['content' => view('admin/wordcloud', $data)]);
    }

    public function clearWordcloud()
    {
        $db = \Config\Database::connect();
        $db->table('wordcloud_entries')->truncate();
        return redirect()->to(base_url('admin/wordcloud'))->with('success', 'ล้างข้อมูล WordCloud เรียบร้อย');
    }
}
