<?php

namespace App\Controllers;

use App\Models\WordCloudModel;

class WordCloud extends BaseController
{
    protected $wordCloudModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->wordCloudModel = new WordCloudModel();
    }

    public function index()
    {
        return view('wordcloud/index', [
            'title' => 'WordCloud',
            'total' => $this->wordCloudModel->getTotalEntries(),
        ]);
    }

    public function store()
    {
        $word1 = trim($this->request->getPost('word1'));
        $word2 = trim($this->request->getPost('word2'));
        $word3 = trim($this->request->getPost('word3'));

        if (empty($word1) || empty($word2) || empty($word3)) {
            return view('wordcloud/index', [
                'title' => 'WordCloud',
                'error' => 'กรุณากรอกคำให้ครบ 3 คำ',
                'total' => $this->wordCloudModel->getTotalEntries(),
                'old'   => compact('word1', 'word2', 'word3'),
            ]);
        }

        $this->wordCloudModel->insert([
            'word1' => $word1,
            'word2' => $word2,
            'word3' => $word3,
        ]);

        return redirect()->to(base_url('wordcloud'))->with('success', 'บันทึกคำเรียบร้อย! คำของคุณถูกเพิ่มใน WordCloud แล้ว');
    }

    public function display()
    {
        return view('wordcloud/display', [
            'title' => 'WordCloud Display',
            'total' => $this->wordCloudModel->getTotalEntries(),
        ]);
    }

    public function data()
    {
        $words = $this->wordCloudModel->getWordFrequency();
        return $this->response->setJSON($words);
    }
}
