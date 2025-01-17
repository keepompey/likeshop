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
namespace app\admin\logic;
use app\common\server\ConfigServer;
use think\Db;

class RechargeLogic{
    public static function templatelists(){
        $list = Db::name('recharge_template')->where(['del'=>0])->select();
        foreach ($list as &$item){
            $item['money'] && $item['money'] = '￥'.$item['money'];
            $item['give_money'] && $item['give_money'] = '￥'.$item['give_money'];
        }
        return $list;
    }

    public static function getRechargeConfig(){
       $config =  [
           'open_racharge'  => ConfigServer::get('recharge','open_racharge',0),
           'give_integral'  => ConfigServer::get('recharge', 'give_integral', ''),
           'give_growth'    => ConfigServer::get('recharge', 'give_growth', ''),
           'min_money'      => ConfigServer::get('recharge', 'min_money', ''),
       ];
       return [$config];
    }
    public static function setRecharge($post){
        ConfigServer::set('recharge','open_racharge',$post['open_racharge']);
        ConfigServer::set('recharge','give_integral',$post['give_integral']);
        ConfigServer::set('recharge','give_growth',$post['give_growth']);
        ConfigServer::set('recharge','min_money',$post['min_money']);
    }

    public static function add($post){
        $new = time();
        $add_data = [
            'money'         => $post['money'],
            'give_money'    => $post['give_money'],
            'sort'          => $post['sort'],
            'is_recommend'  => $post['is_recommend'],
            'create_time'   => $new,
            'update_time'   => $new,
        ];
        return Db::name('recharge_template')->insert($add_data);
    }

    public static function edit($post){
        $new = time();
        $update_data = [
            'money'         => $post['money'],
            'give_money'    => $post['give_money'],
            'sort'          => $post['sort'],
            'is_recommend'  => $post['is_recommend'],
            'update_time'   => $new,
        ];
        return Db::name('recharge_template')->where(['id'=>$post['id']])->update($update_data);
    }

    public static function getRechargeTemplate($id){
        return Db::name('recharge_template')->where(['id'=>$id])->find();
    }


    public static function del($id){
        return Db::name('recharge_template')->where(['id'=>$id])->update(['update_time'=>time(),'del'=>1]);
    }
}