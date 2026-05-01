<?php

namespace App\Controllers;

class Files extends BaseController
{
    public function serve(string $filename)
    {
        $path = WRITEPATH . 'uploads/' . $filename;

        if (!file_exists($path)) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        $mime = mime_content_type($path);
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Length', filesize($path))
            ->setBody(file_get_contents($path));
    }
}
