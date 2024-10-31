<?php

namespace App\Data;

use App\Models\Post;

class PostResult
{
    public Post $post;

 /**
  * コンストラクタ
  *
  * @param $post 投稿情報を受け取ります
  */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}

