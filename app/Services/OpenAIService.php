<?php

namespace App\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;

class OpenAIService {

    protected $client;

    public function __construct() {
        $apiKey = env('OPENAI_API_KEY');

        if (!$apiKey) {
            Log::error('OpenAI API key is missing.');
            throw new \Exception('OpenAI API key is not set.');
        }

        $this->client = OpenAI::client($apiKey);
    }

    public function levelQuestion($question) {
        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You categorize questions as Easy, Medium, or Hard. Only return one word: Easy, Medium, or Hard.'],
                    ['role' => 'user', 'content' => "Categorize this question: $question"]
                ],
            ]);

            // Extract response
            $responseText = trim($response['choices'][0]['message']['content'] ?? '');

            // Log response for debugging
            Log::info('OpenAI response:', ['response' => $responseText]);

            // Ensure compatibility with database
            if ($responseText === 'Medium') {
                $responseText = 'Normal';
            }

            return $responseText;
        } catch (\Exception $e) {
            Log::error('OpenAI API error: ' . $e->getMessage(), ['stack_trace' => $e->getTraceAsString()]);
            return 'Normal'; // Fallback to a default difficulty level
        }
    }
}
