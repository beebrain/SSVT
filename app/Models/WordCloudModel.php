<?php

namespace App\Models;

use CodeIgniter\Model;

class WordCloudModel extends Model
{
    protected $table         = 'wordcloud_entries';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['word1', 'word2', 'word3'];
    protected $useTimestamps = false;

    public function getWordFrequency()
    {
        $db      = \Config\Database::connect();
        $entries = $db->table('wordcloud_entries')->get()->getResultArray();

        $freq = [];
        foreach ($entries as $row) {
            foreach (['word1', 'word2', 'word3'] as $col) {
                $word = mb_strtolower(trim($row[$col]));
                if ($word !== '') {
                    $freq[$word] = ($freq[$word] ?? 0) + 1;
                }
            }
        }

        arsort($freq);

        $result = [];
        foreach ($freq as $word => $count) {
            $result[] = ['text' => $word, 'size' => $count];
        }

        return $result;
    }

    public function getTotalEntries()
    {
        return $this->countAll();
    }
}
