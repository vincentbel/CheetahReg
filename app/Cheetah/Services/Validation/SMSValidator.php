<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/1
 * Time: 20:35
 */

namespace Cheetah\Services\Validation;

/**
 * Class SMSValidator
 * 发送短信验证码和验证短信验证码类
 *
 * @package Cheetah\Services\Validation
 */




class SMSValidator
{
    //短信验证平台连接
    private $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";

    //手机号码
    private $phoneNumber;

    //验证码
    private $mobileCode;


    /**
     * @param $phoneNumber
     *
     * @return 如果发送成功，返回true, 如果发送失败，返回false
     */
    public function sendSMS($phoneNumber)
    {
        //产生验证码,4位
        $mobileCode = $this->random(4,1);
        if(empty($phoneNumber)){
            exit('手机号码不能为空');
        }

        //密码可以使用明文密码或使用32位MD5加密
        $postData = "account=c_jmy&password=zh@jmy&mobile=".$phoneNumber."&content=".rawurlencode("（猎豹挂号网）您的验证码是：".$mobileCode."。请不要把验证码泄露给其他人。");

        $gets =$this->xmlToArray($this->post($postData, $this->target));
        if($gets['SubmitResult']['code']==2){
            //将验证码存入session
            Seession::put('mobileCode',$mobileCode);
            return true;
        } elseif ($gets['SubmitResult']['code']==1) {
            return false;
        } else {
            // 账户余额不足，通知管理员
        }
        return false;
        
    }

    /**
     *产生验证码
     */
    public function random($length = 6 , $numeric = 0)
     {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }
    /**
     * 产生与验证平台的连接
     *
     * @param $curlPost  传给验证平台的字符串，包含账号，密码，手机号，验证码等信息
     * @param $url       验证平台的连接地址
     * @return $returnStr    返回一个XML文件，里面包括是否发送成功等信息
     */
    private function post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $returnStr = curl_exec($curl);
        curl_close($curl);
        return $returnStr;
    }

    /**
     * 将验证平台返回的XML文件转换为数组
     *
     * @param $xml
     * @return $arr
     */
    private function xmlToArray($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xmlToArray( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    /**
     * 检验验证码是否正确
     * 如果正确，返回TRUE 如果错误，返回FALSE
     * @param $mobileCode  用户输入的验证码
     * @return bool
     */
    public function verifySMSCode($mobileCode)
    {
        if($mobileCode==Session::get('mobileCode'))
        {
            return true;
        }
        return false;
    }
}