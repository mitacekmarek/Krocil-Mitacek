<?php

function truncate_text(string $text, int $length = 100): string
    {
        $text = strip_tags($text);
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . '...';
    }

    