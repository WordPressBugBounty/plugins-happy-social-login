<?php
namespace HappySocialLogin\Hybridauth\Storage;

use Hybridauth\Storage\StorageInterface;

class MemoryStorage implements StorageInterface {
    private $data = [];

    public function get($key) {
        if (str_contains($key, 'authorization_state') || str_contains($key, 'code_verifier')) {
            return $this->getFromCookie($key);
        }

        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function set($key, $value) {
        if (str_contains($key, 'authorization_state') || str_contains($key, 'code_verifier')) {
            $this->setInCookie($key, $value);
        } else {
            $this->data[$key] = $value;
        }
    }

    public function delete($key) {
        if (str_contains($key, 'authorization_state') || str_contains($key, 'code_verifier')) {
            $this->deleteFromCookie($key);
        } else {
            unset($this->data[$key]);
        }
    }

    public function deleteMatch($key) {}

    public function clear() {}

    private function setInCookie($key, $value) {
        $key = str_replace('.', '_', $key);
        // Sanitize the value before setting the cookie
        $value = sanitize_text_field($value);
        setcookie($key, $value, time() + 3600, '/');
    }

    private function getFromCookie($key) {
        $key = str_replace('.', '_', $key);
        // Ensure the cookie value is unslashed and sanitized
        return isset($_COOKIE[$key]) ? sanitize_text_field(wp_unslash($_COOKIE[$key])) : null;
    }

    private function deleteFromCookie($key) {
        $key = str_replace('.', '_', $key);
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            // Expire the cookie
            setcookie($key, '', time() - 3600, '/');
        }
    }
}
