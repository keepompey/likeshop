<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------

// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\common\model;

use think\Model;

class Withdraw extends Model
{

    //提现类型
    const TYPE_BALANCE = 1; // 提现到钱包余额
    const TYPE_WECHAT_CHANGE = 2; // 提现到微信零钱
    const TYPE_WECHAT_CODE = 3;  // 提现到微信收款码
    const TYPE_ALI_CODE = 4;  // 提现到支付宝收款码
    const TYPE_BANK = 5;  // 提现到银行卡


    //提现状态
    const STATUS_WAIT = 1; // 待提现
    const STATUS_ING = 2; //  提现中
    const STATUS_SUCCESS  = 3; // 提现成功
    const STATUS_FAIL  = 4; //提现失败

    //提现状态
    public static function getStatusDesc($status = true)
    {
        $desc = [
            self::STATUS_WAIT => '待提现',
            self::STATUS_ING => '提现中',
            self::STATUS_SUCCESS => '提现成功',
            self::STATUS_FAIL => '提现失败'
        ];
        if ($status === true) {
            return $desc;
        }
        return $desc[$status] ?? '';
    }

    //提现类型
    public static function getTypeDesc($status = true)
    {
        $desc = [
            self::TYPE_BALANCE => '钱包余额',
            self::TYPE_WECHAT_CHANGE => '微信零钱',
            self::TYPE_BANK => '银行卡',
            self::TYPE_WECHAT_CODE => '微信收款码',
            self::TYPE_ALI_CODE => '支付宝收款码',
        ];
        if ($status === true) {
            return $desc;
        }
        return $desc[$status] ?? '';
    }
}