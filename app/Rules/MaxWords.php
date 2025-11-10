<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxWords implements Rule
{
    protected $maxWords;
    protected $attribute;

    /**
     * Create a new rule instance.
     *
     * @param int $maxWords
     */
    public function __construct($maxWords)
    {
        $this->maxWords = $maxWords;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        
        if (empty($value)) {
            return true; // Let required rule handle empty values
        }

        // Remove HTML tags
        $text = strip_tags($value);
        
        // Count words: split by whitespace and filter empty strings
        // This works better for Vietnamese and other languages
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = count($words);
        
        return $wordCount <= $this->maxWords;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $attributeNames = [
            'subject' => 'Chủ đề',
            'message' => 'Nội dung',
        ];
        
        $attributeName = $attributeNames[$this->attribute] ?? 'Trường này';
        
        return "{$attributeName} không được vượt quá {$this->maxWords} từ.";
    }
}

