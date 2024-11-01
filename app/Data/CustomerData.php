<?php

namespace App\Data;

class CustomerData
{
    public string $name;
    public string $kana;
    public string $tel;
    public string $email;
    public string $postcode;
    public string $address;
    public string $birthday;
    public int $gender;
    public string $memo;

    /**
     * コンストラクタ
     *
     * @param string $name 名前
     * @param string $kana カナ
     * @param string $tel 電話番号
     * @param string $email メールアドレス
     * @param string $postcode 郵便番号
     * @param string $address 住所
     * @param string $birthday 誕生日
     * @param int $gender 性別
     * @param string $memo めも
     */
    public function __construct(
        string $name,string $kana,string $tel,
        string $email,string $postcode,string $address,
        string $birthday,int $gender,string $memo) 
    {
        $this->name = $name;
        $this->kana = $kana;
        $this->tel = $tel;
        $this->email = $email;
        $this->postcode = $postcode;
        $this->address = $address;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->memo = $memo;
    }
}
