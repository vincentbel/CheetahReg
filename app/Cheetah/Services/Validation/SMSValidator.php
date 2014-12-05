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

session_start();

header("Content-type:text/html; charset=UTF-8");


class SMSValidator
{
    /**
     * @param $phoneNumber
     *
     * @return 如果发送成功，返回true, 如果发送失败，返回false
     */
    $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";//短信验证平台连接
    $phoneNumber;//手机号码
    $mobileCode;//验证码
    public function sendSMS($phoneNumber)
    {
        


        $mobileCode = random(4,1);//产生验证码
        if(empty($phoneNumber)){
            exit('手机号码不能为空');
        }
        $postData = "account=cf_jmy&password=zh@jmy&mobile=".$phoneNumber."&content=".rawurlencode("（猎豹挂号网）您的验证码是：".$mobileCode."。请不要把验证码泄露给其他人。");
        //密码可以使用明文密码或使用32位MD5加密
        $gets =  xml_to_array(Post($postData, $target));
        if($gets['SubmitResult']['code']==2){
            Seession::put('mobileCode',$mobileCode);//将验证码存入session
            return true;
        } elseif ($gets['SubmitResult']['code']==1)) {
            return false;
        } else {
            // 账户余额不足，通知管理员
        }
        return false;
        
    }

    /**
     *
     */
    //产生4位验证码
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
    //产生与验证平台的连接
    private function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    private function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    //验证码是否正确
    public function verifySMSCode($mobileCode)
    {
        if($mobileCode==Session::get('mobileCode'))
        {
            return true;
        }
        return false;
    }
}
?>