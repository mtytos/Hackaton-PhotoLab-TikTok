<?php


class ClientPhotolab
{
    const API_ENDPOINT = 'http://api-soft.photolab.me';
    const API_UPLOAD_ENDPOINT = 'http://upload-soft.photolab.me/upload.php';
    const API_ENDPOINT_PROXY = 'http://api-proxy-soft.photolab.me';

    private $fileLog = 'access.log';

    public function __construct($fileLog = '')
    {
        if ($fileLog) {
            $this->fileLog = $fileLog;
        }
    }

    public function imageUpload($image)
    {
        if (!strlen($image) || !file_exists(realpath($image))) {
            throw new Exception('image empty or file not exists');
        }
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($image);
        } else { //
            $cFile = '@' . realpath($image);
        }
        $files = ['no_resize' => 1, 'file1' => $cFile];

        return $this->_query(self::API_UPLOAD_ENDPOINT, [], $files);
    }

    public function templateUpload($resources)
    {
        if (!strlen($resources) || !file_exists(realpath($resources))) {
            throw new Exception('resources empty or file not exists');
        }
        if (function_exists('curl_file_create')) { // php 5.5+
            $cResources = curl_file_create($resources);
        } else { //
            $cResources = '@' . realpath($resources);
        }
        $files = ['resources' => $cResources];
        $endpoint = sprintf("%s/template_upload.php", self::API_ENDPOINT_PROXY);
        return $this->_query($endpoint, [], $files);
    }

    public function templateProcess($templateName, $contents)
    {
        $form = [
            'template_name' => $templateName
        ];
        foreach ($contents as $i => $content) {
            $t = '[' . ($i + 1) . ']';
            $form['image_url' . $t] = $content['url'];
            foreach (['crop', 'flip', 'rotate'] as $index) {
                if (isset($content[$index])) {
                    $form[$index . $t] = $content[$index];
                }
            }
        }
        $endpoint = sprintf("%s/template_process.php", self::API_ENDPOINT);
        return $this->_query($endpoint, $form);
    }

    public function photolabProcess($templateName, $contents)
    {
        $form = [
            'template_name' => $templateName
        ];
        $i = 1;
        foreach ($contents as $content) {
            $t = '[' . $i . ']';
            $form['image_url' . $t] = $content['url'];
            foreach (['crop', 'flip', 'rotate'] as $index) {
                if (isset($content[$index])) {
                    $form[$index . $t] = $content[$index];
                }
            }
            $i++;
        }
        $endpoint = sprintf("%s/template_process.php", self::API_ENDPOINT);
        return $this->_query($endpoint, $form);
    }

    public function photolabSteps($comboId)
    {
        $form = [
            'combo_id' => $comboId
        ];
        $endpoint = sprintf("%s/photolab_steps.php", self::API_ENDPOINT);
        $result = $this->_query($endpoint, $form);
        $result = json_decode($result);
        $jsonError = json_last_error();
        if ($jsonError != JSON_ERROR_NONE) {
            throw new Exception('Json  Error#' . $jsonError);
        }
        return $result;
    }

    public function photolabStepsAdvanced($comboId)
    {
        $form = [
            'combo_id' => $comboId
        ];
        $endpoint = sprintf("%s/photolab_steps_advanced.php", self::API_ENDPOINT);

        $result = $this->_query($endpoint, $form);
        $result = json_decode($result);

        $jsonError = json_last_error();
        if ($jsonError != JSON_ERROR_NONE) {
            throw new Exception('Json  Error#' . $jsonError);
        }
        return $result;
    }

    public function downloadFile($endpoint, $dst)
    {
        file_put_contents($dst, $this->getResource($endpoint));
    }

    /**
     * @param string $url
     *
     * @return response body string
     */
    protected function getResource($url) {
        if (!function_exists('curl_init')){
            throw new \Exception('CURL is not installed!', 500);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    protected function _query($endpoint, $data = [], $files = [])
    {
        $data = array_merge($data, $files);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => "Mozilla/4.0 (compatible;)",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ));
        $respBody = curl_exec($ch);

        $this->logger("%asctime% - %file%:%line% %message%",
            ["level" => "info", "message" => sprintf('response: %s', $respBody),
                "file" => __FILE__, "line" => __LINE__, "asctime" => date("Y-m-d h:m:s")]
        );
        if (!$respBody) {
            curl_close($ch);
            throw new Exception('Curl error #' . curl_error($ch));
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode != 200) {
            throw new Exception(sprintf("_query: %s, error: %s", $endpoint, $respBody));
        }
        curl_close($ch);
        return $respBody;
    }

    protected function logger($message, array $data)
    {
        if (!$this->fileLog){
            return;
        }
        foreach ($data as $key => $val) {
            $message = str_replace("%{$key}%", $val, $message);
        }
        $message .= PHP_EOL;

        return file_put_contents($this->fileLog, $message, FILE_APPEND);
    }
}