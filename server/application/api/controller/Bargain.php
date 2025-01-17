<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------
namespace app\api\controller;
use app\api\logic\BargainLogic;

class Bargain extends ApiBase{

    public $like_not_need_login = ['barginNumber', 'lists','detail','closeBargain'];

    /**
     * Notes:获取砍价成功人数
     * @author:  2021/2/23 16:11
     */
    public function barginNumber(){
        $number = BargainLogic::barginNumber();
        $this->_success('获取成功',$number);
    }


    /**
     * Notes: 砍价列表
     * @author:  2021/2/23 15:54
     */
    public function lists(){

        $list = BargainLogic::lists($this->page_no, $this->page_size);
        $this->_success('获取成功',$list);
    }

    /**
     * Notes:砍价活动详情
     * @author:  2021/2/23 17:44
     */
    public function detail(){
        $bargain_id = $this->request->get('bargain_id');
        $result = $this->validate(['bargain_id'=>$bargain_id],'app\api\validate\Bargain.detail');
        if(true === $result){
            $detail = BargainLogic::detail($bargain_id);
            $this->_success('获取成功',$detail);
        }
        $this->_error($result);

    }

    /**
     * Notes:发起砍价
     * @author:  2021/2/23 18:43
     */
    public function sponsor(){
        $post_data = $this->request->post();
        $result = $this->validate($post_data,'app\api\validate\Bargain.sponsor');
        if(true === $result){
            $data = BargainLogic::sponsor($post_data,$this->user_id);
            $this->_success($data['msg'],$data['data'],$data['code'],$data['show']);
        }
        $this->_error($result);
    }

    /**
     * Notes:砍价助力
     * @author:  2021/2/25 18:21
     */
    public function knife(){
        $id = $this->request->post('id');
        $result = $this->validate(['id'=>$id,'user_id'=>$this->user_id],'app\api\validate\Bargain.knife');
        if(true === $result){
            $data = BargainLogic::knife($id,$this->user_id);
            $this->_success($data['msg'],$data['data'],$data['code'],$data['show']);
        }
        $this->_error($result);
    }
    
    /**
     * Notes:砍价订单列表
     * @author:  2021/2/24 17:15
     */
    public function orderList(){
        $type = $this->request->get('type','-1');
        $list = BargainLogic::orderList($type,$this->user_id,$this->page_no,$this->page_size);
        $this->_success('获取成功',$list);
    }


    /**
     * Notes:砍价详情
     * @author:  2021/2/24 15:36
     */
    public function bargainDetail(){
        $id = $this->request->get('id');
        $result = $this->validate(['id'=>$id,'user_id'=>$this->user_id],'app\api\validate\Bargain.bargainDetail');
        if(true === $result){
            $detail = BargainLogic::bargainDetail($id,$this->user_id);
            $this->_success('获取成功',$detail);
        }
        $this->_error($result);
    }



    /**
     * Notes:关闭砍价订单
     * @author:  2021/2/26 16:36
     */
    public function closeBargain(){
        $id = $this->request->post('id');
        if($id){
            BargainLogic::closeBargain($id);
        }
        $this->_success('');
    }






}