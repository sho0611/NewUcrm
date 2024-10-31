<?php

namespace App\Data;



class CommentData
{
    public string $content;
    public int $post_id;
    public int $customer_id;

  
    /**
     * コンストラクタ
     *
     * @param string $content コメント内容
     * @param integer $post_id 投稿のID
     * @param integer $customer_id 顧客のId
     */
    public function __construct(string $content,int $post_id,int $customer_id)
    {
        $this->content = $content;
        $this->post_id = $post_id;
        $this->customer_id = $customer_id;
    }
}