<?php

namespace ColdRockSoftware;

use CURLFile;
use Exception;

class TgPictureUploader
{
    protected string $tgApiKey;
    protected string $tgChatId;

    /**
     * Post random picture from content/ folder to TG channel
     *
     * @return void
     */
    public function start(): void
    {
        try {
            $this->parseEnv();
            $pictures = glob('content' . DIRECTORY_SEPARATOR . '*');

            if (count($pictures) == 0) {
                echo 'No pictures found' . PHP_EOL;
                exit;
            }

            $filePath = $pictures[array_rand($pictures)];
            $result = $this->sendPicture($filePath);
            $this->saveLogEntity($filePath, $result);
            unlink($filePath);
        } catch (Exception $e) {
            $filePath = $filePath ?? 'undefined';
            $result = $result ?? 'Error: ' . $e->getMessage();
            $this->saveLogEntity($filePath, $result);
        }
    }

    /**
     * Send picture to TG channel
     *
     * @param string $filePath
     * @return bool|string
     */
    protected function sendPicture(string $filePath): bool|string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:multipart/form-data']);
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot$this->tgApiKey/sendPhoto");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'chat_id' => $this->tgChatId,
            'photo' => new CURLFile(realpath($filePath)),
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Save log entity to file named with a current date
     *
     * @param string $filePath
     * @param string $message
     * @return void
     */
    protected function saveLogEntity(string $filePath, string $message): void
    {
        $logFileName = date('Y-m-d') . '.log';
        $logFilePath = 'logs' . DIRECTORY_SEPARATOR . $logFileName;
        $logItem = '[' . date('Y-m-d H:i:s') . ']: ' . $filePath . '; ' . $message;
        file_put_contents($logFilePath, $logItem . PHP_EOL, FILE_APPEND);
    }

    /**
     * Parse .env file into configurations
     *
     * @return void
     */
    protected function parseEnv(): void
    {
        $configNodes = [];
        $envFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env';
        $envContent = explode(PHP_EOL, file_get_contents($envFile));

        foreach ($envContent as $config) {
            list($key, $value) = explode('=', $config, 2);
            $value = trim($value, '"');
            $configNodes[$key] = $value;
        }

        $this->tgApiKey = $configNodes['TG_BOT_API_KEY'];
        $this->tgChatId = $configNodes['TG_CHAT_ID'];
    }
}