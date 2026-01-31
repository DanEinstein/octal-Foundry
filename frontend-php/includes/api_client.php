<?php
/**
 * API Client for FastAPI Backend
 * Handles HTTP requests using cURL
 * 
 * Usage:
 *   require_once 'includes/api_client.php';
 *   $client = new ApiClient();
 *   $response = $client->generateRoadmap([
 *       'unit_code' => 'CIT 301',
 *       'unit_name' => 'Machine Learning'
 *   ]);
 */

class ApiClient {
    private string $baseUrl;
    private int $timeout;
    
    /**
     * Create API client
     */
    public function __construct() {
        // Load from environment or use default
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '#') === 0) continue;
                if (strpos($line, '=') === false) continue;
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
        
        $this->baseUrl = $_ENV['FASTAPI_URL'] ?? 'http://localhost:8000';
        $this->timeout = 60; // 60 seconds for AI processing
    }
    
    /**
     * Generate learning roadmap via AI
     * 
     * @param array $unitData Unit information
     * @return array API response with success status and roadmap/error
     */
    public function generateRoadmap(array $unitData): array {
        // unitData can now include 'career_path' and 'concurrent_units'
        return $this->post('/api/roadmap/generate', $unitData);
    }
    
    /**
     * Get AI coaching hint
     * 
     * @return array Hint data
     */
    public function getCoachHint(): array {
        return $this->get('/api/coach/hint');
    }
    
    /**
     * Check backend health
     * 
     * @return array Health status
     */
    public function healthCheck(): array {
        return $this->get('/health');
    }
    
    /**
     * Send POST request
     * 
     * @param string $endpoint API endpoint
     * @param array $data Request body data
     * @return array Response data
     */
    private function post(string $endpoint, array $data): array {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false, // Disable for localhost dev
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        
        // Handle cURL errors
        if ($errno) {
            error_log("API cURL Error ($errno): $error");
            return [
                'success' => false,
                'error' => 'Failed to connect to AI service',
                'details' => $error
            ];
        }
        
        // Parse response
        $decoded = json_decode($response, true);
        
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("API JSON Error: " . json_last_error_msg());
            return [
                'success' => false,
                'error' => 'Invalid response from AI service',
                'details' => 'JSON parse error'
            ];
        }
        
        // Check HTTP status
        if ($httpCode >= 400) {
            return [
                'success' => false,
                'error' => $decoded['error'] ?? $decoded['detail'] ?? 'API error',
                'details' => "HTTP $httpCode"
            ];
        }
        
        return $decoded;
    }
    
    /**
     * Send GET request
     * 
     * @param string $endpoint API endpoint
     * @param array $params Query parameters
     * @return array Response data
     */
    private function get(string $endpoint, array $params = []): array {
        $url = $this->baseUrl . $endpoint;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
            ],
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        
        if ($errno) {
            error_log("API cURL Error ($errno): $error");
            return [
                'success' => false,
                'error' => 'Failed to connect to AI service',
                'details' => $error
            ];
        }
        
        $decoded = json_decode($response, true);
        
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid response',
                'details' => 'JSON parse error'
            ];
        }
        
        return $decoded;
    }
}
