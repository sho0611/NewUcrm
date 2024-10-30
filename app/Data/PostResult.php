<?php

namespace App\Data;

class PostResult
{
    public array $posts;

    public function __construct(array $posts)
    {
        $this->posts = $posts;
        
    }
}

