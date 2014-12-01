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
     */
    public function isIdCardAndNameMatched($idCard, $name)
    {
        return "function isIdCardAndNameMatched() retched.";
    }

    /**
     * 判断身份证号是否正确
     * @param $idCard 身份证号
     */
    public function isIdCardCorrect($idCard)
    {
        return "function isIdCardCorrect() retched.";
    }
}