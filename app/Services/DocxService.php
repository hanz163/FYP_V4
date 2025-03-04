<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;

class DocxService {

    public function extractQuestions($filePath) {
        try {
            \Log::info('Extracting questions from file: ' . $filePath);

            // Verify file exists
            if (!file_exists($filePath)) {
                \Log::error('File does not exist at resolved path: ' . $filePath);
                throw new \Exception('File does not exist.');
            }

            // Extract text from DOCX
            $text = $this->readDocxText($filePath);
            \Log::info('Extracted text from DOCX:', ['text' => $text]);

            if (empty(trim($text))) {
                \Log::error('Extracted text is empty. Possible file format issue.');
                return [];
            }

            return $this->parseQuestions($text);
        } catch (\Exception $e) {
            \Log::error('Error extracting questions: ' . $e->getMessage());
            throw $e;
        }
    }

    private function readDocxText($filePath) {
        $content = '';
        $zip = new \ZipArchive();

        if ($zip->open($filePath) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $xmlData = $zip->getFromIndex($index);
                $zip->close();

                // Strip XML tags and extract readable text
                $content = strip_tags(str_replace('</w:t>', ' ', $xmlData));
                $content = preg_replace('/<w:t[^>]*>/', '', $content);
            }
        } else {
            \Log::error('Failed to open .docx file.');
            throw new \Exception('Invalid DOCX file.');
        }

        // Log the extracted text for debugging
        \Log::info('Extracted text from DOCX:', ['content' => $content]);

        return trim($content);
    }

    private function parseQuestions($text) {
        \Log::info('Raw extracted text:', ['text' => $text]);

        // Normalize spaces and ensure each question is separated by a delimiter
        $text = preg_replace("/\s+/", " ", $text);

        // Split the text into individual questions using a more flexible delimiter
        $questionBlocks = preg_split('/(?=\b(Q:|Question:|What|Which|How|Why|When|Where|Who)\b)/i', $text, -1, PREG_SPLIT_NO_EMPTY);

        $questions = [];

        foreach ($questionBlocks as $block) {
            \Log::info('Processing block:', ['block' => $block]);

            // Extract the question text, correct answer, wrong answers, and explanation
            preg_match('/(.*?)\s*Correct Answer:\s*(.*?)\s*Wrong Answer 1:\s*(.*?)\s*Wrong Answer 2:\s*(.*?)\s*Wrong Answer 3:\s*(.*?)\s*Explanation:\s*(.*)/s', $block, $match);

            if (!empty($match)) {
                $questions[] = [
                    'question' => trim($match[1]), // Question text
                    'correct_answer' => trim($match[2]), // Correct answer
                    'wrong_answers' => [
                        trim($match[3]), // Wrong answer 1
                        trim($match[4]), // Wrong answer 2
                        trim($match[5]), // Wrong answer 3
                    ],
                    'explanation' => trim($match[6]), // Explanation
                ];
            } else {
                \Log::warning('Failed to parse question block:', ['block' => $block]);
            }
        }

        if (empty($questions)) {
            \Log::error('No questions matched. Possible format issue.');
        } else {
            \Log::info('Successfully parsed questions:', ['count' => count($questions)]);
        }

        return $questions;
    }
}
