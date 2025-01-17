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

namespace app\admin\controller;


use app\common\server\ConfigServer;

/**
 * 分销设置
 * Class Distribution
 * @package app\admin\controller
 */
class Distribution extends AdminBase
{

    /**
     * 分销会员列表/审核会员列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setting()
    {
        $config = [
            'is_open' => ConfigServer::get('distribution', 'is_open', 1),
            'member_apply' => ConfigServer::get('distribution', 'member_apply', 1),
        ];
        $this->assign('config', $config);
        return $this->fetch('');
    }


    /**
     * 设置分销配置
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function setDistribution()
    {
        $post = $this->request->post();
        if ($post) {
            ConfigServer::set('distribution', 'is_open', $post['is_open']);
            ConfigServer::set('distribution', 'member_apply', $post['member_apply']);
            $this->_success('修改成功');
        }
    }
}