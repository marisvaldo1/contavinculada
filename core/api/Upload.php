<?php
namespace api;

use const AMBIENTE_PUBLICACAO;

class Upload
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @var resource
     */
    private $ch;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $token;

    /**
     * Uploader constructor.
     */
    public function __construct()
    {
        $conf = config('upload')[AMBIENTE_PUBLICACAO];

        $this->baseUrl = $conf['base_url'];
        $this->token = $conf['token'];

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_PROXY, $conf['proxy']);
        curl_setopt($this->ch, CURLOPT_PROXYPORT, $conf['proxy_port']);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $conf['token'],
        ]);
    }

    public function addFile($filename, $mime = null, $baseName = null)
    {
        if (!$mime) {
            $mime = mime_content_type($filename);
        }
        if (!$baseName) {
            $baseName = basename($filename);
        }
        $this->files[] = [
            'filename' => $filename,
            'tipo' => $mime,
            'nome' => $baseName,
        ];
    }

    public function addFromFiles()
    {
        foreach ($_FILES as $file) {
            if (is_array($file['name'])) {
                $n = count($file['name']);
                for ($i = 0; $i < $n; $i++) {
                    $this->addFile($file['tmp_name'][$i], $file['type'][$i], $file['name'][$i]);
                }
            } else {
                $this->addFile($file['tmp_name'], $file['type'], $file['name']);
            }
        }
    }

    public function post()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->baseUrl . '/arquivos');
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, null);
        $post = [];
        foreach ($this->files as $k => $f) {
            $file = curl_file_create($f['filename'], $f['tipo'], $f['nome']);
            $post['file_' . $k] = $file;
        }
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        $res = curl_exec($this->ch);
        if (!$res) {
            throw new \Exception('Erro ao fazer upload: ' . curl_error($this->ch));
        }
        return json_decode($res, true);
    }

    public function delete($nome)
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->baseUrl . '/arquivos/' . $nome);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->ch, CURLOPT_POST, 0);
        curl_exec($this->ch);
    }

    public function lista()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->baseUrl . '/arquivos');
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, null);
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $res = curl_exec($this->ch);
        if (!$res) {
            throw new \Exception('Erro ao listar arquivos: ' . curl_error($this->ch));
        }
        return json_decode($res, true);
    }

    public function helloWorld()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->baseUrl . '/hello');
        curl_setopt($this->ch, CURLOPT_POST, 0);
        $res = curl_exec($this->ch);
        return $res;
    }
}