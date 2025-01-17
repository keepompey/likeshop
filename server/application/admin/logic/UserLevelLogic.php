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
use app\common\logic\UserLevelLogic as CommonUserLevelLogic;
use app\common\server\UrlServer;
use think\Db;

class UserLevelLogic{

    public static function lists($get){

        $count = Db::name('user_level')->where(['del'=>0])->count();
        $list = Db::name('user_level')
                ->where(['del'=>0])
                ->page($get['page'], $get['limit'])
                ->select();

        foreach ($list as &$item){
            $item['privilege_list'] = '';

            $item['image'] = UrlServer::getFileUrl($item['image']);
            $item['background_image'] = UrlServer::getFileUrl($item['background_image']);
            if($item['privilege']){
                $privileges = explode(',',$item['privilege']);
                $privilege_list = Db::name('user_privilege')
                                ->where(['del'=>0,'id'=>$privileges])
                                ->column('name');
                $item['privilege_list'] = $privilege_list ? implode('、',$privilege_list) : '';
            }

        }
        return ['count' => $count, 'list' => $list];
    }

    public static function add($post){
        $now = time();
        $post['create_time'] = $now;
        $post['update_time'] = $now;
        $id = Db::name('user_level')->insertGetId($post);
        if($id){
            CommonUserLevelLogic::updateAllUserLevel($id);
        }
        return $id;
    }

    public static function edit($post){
        $now = time();
        $post['update_time'] = $now;
        Db::name('user_level')->where(['id'=>$post['id']])->update($post);
        CommonUserLevelLogic::updateAllUserLevel($post['id']);
        return true;
    }

    public static function del($id){
        $now = time();
        $post['update_time'] = $now;
        $post['del'] = 1;
        return Db::name('user_level')->where(['id'=>$id])->update($post);
    }

    public static function getUserLevel($id){
        $detail = Db::name('user_level')->where(['id'=>$id,'del'=>0])->find();
        $detail['abs_image'] = UrlServer::getFileUrl($detail['image']);
        $detail['abs_background_image'] = UrlServer::getFileUrl($detail['background_image']);
        return $detail;
    }

    public static function getPrivilegeList(){
        return Db::name('user_privilege')->where(['del'=>0])->field('id,name')->select();
    }

}