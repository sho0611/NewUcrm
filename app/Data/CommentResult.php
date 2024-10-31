<?php

namespace App\Data;

use App\Models\Comment;

class CommentResult
{
    public Comment $comment;

 /**
  * コンストラクタ
  *
  * @param $comment コメント内容を受け取ります
  */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}

