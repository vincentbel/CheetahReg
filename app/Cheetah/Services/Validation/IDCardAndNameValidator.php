<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/1
 * Time: 20:59
 */

namespace Cheetah\Services\Validation;

/**
 * Class IdCardAndNameValidator
 * 验证身份证号和姓名，包括验证身份证号是否正确，以及身份证号和姓名是否一致
 *
 * @package Cheetah\Services\Validation
 */
class IdCardAndNameValidator
{

    /**
     * 验证身份证号和姓名是否匹配
     * @param $idCard 身份证号
     * @param $name 姓名
     *
     * @return bool
     */
    public static function isIdCardAndNameMatched($idCard, $name)
    {
        //身份证号格式正确且姓名不为空
        if (IdCardAndNameValidator::isIdCardCorrect($idCard) && !empty($name)) {

            // 由于资源不足，不能验证身份证号和姓名是否匹配，所以这里进行 伪验证
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断身份证号是否正确
     * @param $idCard 身份证号
     * @return bool
     */
    public static function isIdCardCorrect($idCard)
    {
        // 身份证号必须是string类型
        if (is_string($idCard)) {

            // 身份证号长度
            $idCardLength = strlen($idCard);

            // 身份证号长度为15位或者18位
            if ($idCardLength == 15 || $idCardLength == 18) {
                // 身份证号最后一位校验位
                $checkCode = $idCard[$idCardLength - 1];

                // 身份证号前17为是数字，最后一位是数字或者是'x'
                if (is_numeric(substr($idCard, 0, $idCardLength - 1)) && (is_numeric($checkCode) || strtolower($checkCode) === 'x')) {
                    return true;
                }
            }
        }

        return false;
    }
}