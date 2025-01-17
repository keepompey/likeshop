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

use app\common\server\CrontabServer;
use Cron\CronExpression;
use think\Console;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\Exception;
use think\facade\Debug;
use think\facade\Log;

class Crontab extends Command
{
    protected function configure()
    {
        $this->setName('crontab')
            ->setDescription('定时任务');
    }

    /**
     * 启动定时任务守护进程
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function execute(Input $input, Output $output)
    {
        Log::close();

        $time = time();

        $crons = Db::name('dev_crontab')
            ->where(['status' => 1])
            ->select();
        if (empty($crons)) {
            return;
        }

        foreach ($crons as $cron) {
            //规则错误，不执行
            if (CronExpression::isValidExpression($cron['expression']) === false) {
                continue;
            }
            //未到时间，不执行
            $cron_expression = CronExpression::factory($cron['expression']);
            $next_time = $cron_expression->getNextRunDate(date('Y-m-d H:i:s', $cron['last_time']))->getTimestamp();
            if ($next_time >= $time) {
                continue;
            }

            //开始执行
            try {
                Debug::remark('begin');
                $parameter = explode(' ', $cron['parameter']);
                if (is_array($parameter) && !empty($cron['parameter'])) {
                    Console::call($cron['command'], $parameter);
                } else {
                    Console::call($cron['command']);
                }
                Db::name('dev_crontab')
                    ->where(['id' => $cron['id']])
                    ->update(['error' => '']);
            } catch (\Exception $e) {
                Db::name('dev_crontab')
                    ->where(['id' => $cron['id']])
                    ->update(['error' => $e->getMessage(), 'status' => 3]);
            } finally {
                Debug::remark('end');
                $range_time = Debug::getRangeTime('begin', 'end');
                $max_time = max($cron['max_time'], $range_time);
                Db::name('dev_crontab')
                    ->where(['id' => $cron['id']])
                    ->update(['last_time' => $time, 'time' => $range_time, 'max_time' => $max_time]);
            }
        }
    }


}