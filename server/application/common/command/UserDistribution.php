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

namespace app\common\command;

use app\common\model\Pay;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class UserDistribution extends Command
{
    protected function configure()
    {
        $this->setName('user_distribution')
            ->setDescription('更新会员分销信息');
    }

    protected function execute(Input $input, Output $output)
    {
        $users = Db::name('user u')
            ->field('d.*')
            ->join('user_distribution d', 'd.user_id = u.id')
            ->where(['u.del' => 0])
            ->select();

        if (!$users){
            return true;
        }

        foreach ($users as $user){

            //粉丝数量
            $where1 = [
                ['first_leader', '=', $user['user_id']],
            ];
            $where2 = [
                ['second_leader', '=', $user['user_id']],
            ];
            $fans = Db::name('user')
                ->whereOr([$where1,$where2])
                ->count();

            //分销订单信息
            $distribution = Db::name('distribution_order_goods')
                ->field('sum(money) as money, count(id) as order_num')
                ->where(['user_id' => $user['user_id']])
                ->find();

            //订单信息
            $order = Db::name('order')
                ->field('sum(order_amount) as order_amount, count(id) as order_num')
                ->where([
                    'user_id' => $user['user_id'],
                    'pay_status' => Pay::ISPAID,
                    'refund_status' => 0
                ])
                ->find();


            $data = [
                'distribution_order_num' => $distribution['order_num'] ?? 0,
                'distribution_money' => $distribution['money'] ?? 0,
                'order_num' => $order['order_num'] ?? 0,
                'order_amount' => $order['order_amount'] ?? 0,
                'fans' => $fans,
                'update_time' => time(),
            ];

            //更新会员分销信息表
            Db::name('user_distribution')
                ->where('user_id', $user['user_id'])
                ->update($data);
        }
    }

}