<?php

namespace DebugBird;

class DebugBird
{
    private const API_ENDPOINT = 'https://logapi.debugbird.com/post';
    private static bool $logCollectionEnabled = true;
    private static bool $errorCollectionEnabled = true;
    private static string $projectId = '';
    private static string $apiKey = '';

    public static function init(array $options = [])
    {
        self::$logCollectionEnabled = $options['disable_logs'] ?? true;
        self::$errorCollectionEnabled = $options['disable_errors'] ?? true;
        self::$projectId = $options['project_id'] ?? '';
        self::$apiKey = $options['api_key'] ?? '';

        if (self::$errorCollectionEnabled) {
            set_error_handler([self::class, 'handleError']);
            set_exception_handler([self::class, 'handleException']);
        }
    }

    public static function log(string $type, string $content, ?string $tag = null)
    {
        if (!self::$logCollectionEnabled) {
            return;
        }

        self::sendToAPI([
            'postType' => 'log',
            'type' => $type,
            'content' => $content
        ]);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline)
    {
        if (!self::$errorCollectionEnabled) {
            return;
        }

        $content = [
            'code' => $errno,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ];

        self::sendToAPI([
            'postType' => 'crash',
            'type' => 'error',
            'content' => $content
        ]);
    }

    public static function handleException($exception)
    {
        if (!self::$errorCollectionEnabled) {
            return;
        }

        $content = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace()
        ];

        self::sendToAPI([
            'postType' => 'crash',
            'content' => json_encode($content)
        ]);
    }

    private static function sendToAPI(array $payload)
    {
        if (empty(self::$projectId) || empty(self::$apiKey)) {
            return;
        }

        $data = json_encode([
            'projectId' => self::$projectId,
            'apiKey' => self::$apiKey,
            'payload' => [$payload]
        ]);

        $ch = curl_init(self::API_ENDPOINT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
        curl_close($ch);
    }
}

DebugBird::init();
