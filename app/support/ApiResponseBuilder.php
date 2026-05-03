<?php

namespace App\support;
class ApiResponseBuilder
{
    protected bool $success = true;
    protected $data = null;
    protected $meta = null;
    protected $errors = null;
    protected int $statusCode = 200;

    public static function success(): self
    {
        return new self();
    }

    public static function error(string $message, int $code = 400): self
    {
        $instance = new self();
        $instance->success = false;
        $instance->errors = [
            'message' => $message,
            'code' => $code,
        ];
        $instance->statusCode = $code;

        return $instance;
    }

    public function data($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function meta($meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    public function errors($errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    public function status(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function build()
    {
        return response()->json([
            'success' => $this->success,
            'data' => $this->data,
            'meta' => $this->meta,
            'errors' => $this->errors,
        ], $this->statusCode);
    }
}