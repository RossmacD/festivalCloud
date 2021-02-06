<?php

namespace FestivalCloud;

class User
{
    public $name;
    public $email;
    public $phone_no;

    public function __construct(\Aws\Result $awsUser)
    {
        foreach ($awsUser['UserAttributes'] as $key => $val) {
            if ('email' == $val['Name']) {
                $this->email = $val['Value'];
            } elseif ('phone_number' == $val['Name']) {
                $this->phone_no = $val['Value'];
            } elseif ('name' == $val['Name']) {
                $this->name = $val['Value'];
            }
        }
    }
}
