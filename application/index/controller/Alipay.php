<?php

namespace app\index\Controller;

use Yansongda\Pay\Pay;

use Yansongda\Pay\Log;
use think\Facade\Log as lllog;
use think\Db;
class Alipay extends Common
{
    protected $config = [
        'app_id' => '2018122762717134',
        'notify_url' => 'http://admin.yahoosp.cn/index/alipay/notify',
        'return_url' => 'http://www.yahoosp.cn/Personal',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwPeDcJ7bW+t3DFFh7qipRbbMk5aawM5iL+L/eJh9+MAlQ4fE+fSrCeq/EeLNSvLpLiTDgaZJLHjpoEWJqFTK8AVYWfiBu4oJ8yOnlN00bBsPC5BOJOYmf2sgZO3Ml8S21P7trLr36QN5PzAL41taHcS4AF39UdIsWtMkteuywzfp9AhGCFA8TvfmqREP4eaGPGp2qSOozRlJ8TMJVKAdF4uYaJGR5EgiMCW8BL5EAWI/VNAuRtBwMJ5Y+g7VwAh1CuJezxjMdug72L8tsPgLFus1JKkLIl8MwFKIRacSSvCZTHR0ssNL8gfCcFtfYiLSF4QmqflymXFKziNHx294TwIDAQAB',
        'private_key' => 'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQChNfQ1O/enSKxbQL4Hm1UhW20tIl0zCv/d1lxHybM36nR5SROEbKtCdiVguQwpxUvELe+Heeuc6SNVSc7uF78btU3Ctjx1IADoG8t1G7x+p8ypCA2nfT8MTvFFLiAcochu25/vbiYo5eQ60Vsd3Wp62LY07D1BYysQxk6CGBR9aNtG+eSlqk5y1L5WzmfngT6222mvV6FIMZnrJU2svpqodSC33deI3plyeH+Ap5HXVtlP2m79xAVv+94GB6fdljLxbcVIIUdY3evlIi82hxzsb9Ld26sh+zyt/TER8QKzNk33yUqHoPgtDHtuoh6+Xc3H2nQbtnDYSNcMo6NaOa3HAgMBAAECggEBAIqOO8kxNEZTwMdhWrJhAAb+dFRRb7b6IjWBEqkko2NQoDDJ7PtpBrzFnJSIFvsjtl3zeSaAVE/1VMft+utJ/5gJ+L58MHQeQIk9sl2BrD1TbSRuZoXosLKceuORpEnXhtQ48Tow+p02ETW4UE7Xybex4NCVpoQ3foEZX8qSdbHedOOeA+fV3lcSXc7NOdoVL51vbn43s7M21QxD921S2thiXreBoRVCIn1XNmRSk7M9Wu/IaWwV6s7XC60f8Zj6BKqJku2ceDWYa1JV7471SpoHIwoIKRafq+xb4Fh3H1ipvvQnfEy4RWnphKtBrgYKzeu5WtsaIqnht/beeT2OumECgYEA9bw3K+mxP2e+aNpzEf8oltntlqpWQQzShtAmVdjfpXqGz+EuTCbyzaYv+Y3T7ya0TEv3HWCXxy2eOPpjuH0ZjbwP63k+iZ9FcHcq1WX81Z/UoAQyCBZdp732Bsh5dJpCkTzvTKMQspYVD5O189GeVXOnDfqSbUm6K/aElfYCbPECgYEAp/He+GQDGifk4tXrEJLrnuJPWCVAH36xQjjNIVW0GUFzQy6Jb/tcMBQs+3/rHBk0GswEG/fmolfmcGIQLEGNNDl6iBAbjGrMS1UEpSaz7gy/R7u1/nCFtHqgFE+WO+LMoCpsCiwUUFw6iHYznwQkKYghIot8kU2EqBfcKP4QpjcCgYEAks2OaD2EFkz0A8/9TpMQvI4U0h2QyevPGZrgTAj0EvfP8gCkl/nss87EemGwav7EV5BKYNKD2gn2rDNpv5181p+zIwmbwrvT0AxhGnuDQFvrumzHNu2lpcBmakb5yB8gBYRRsYb5QrdGfbfQy4g+/u3IryEyuPeCsSDktHHzoQECgYBE7w5qPgLX9ZAJW2zEvMlhydpvf7q2MrZnTBFGoXru8uJwrOVUxYOtaFqeUH8cZWvxo9P79uD/ubyoXFfvEmj3CrC0sIfeNufr89WYRC6Og0TwGKg269C1p6+VJqWvpwP+qer2sekUowaThJDjsDt3oJyZJU/YBE1zAXgoi0AiOQKBgQCF+CDRFAW1of2RBBsHWjGft8B2S7j8y1mD1Svx6/TbYXrxd0TaS3INCUqO4P7k4OdP6KAFl7wBzecHSSjihl7uk3MNCKr70EwTtWhY6/c5h0qlqtnZK+P3FVoGvCSHR43RUsuFEBUG1bZLm9qPMBSiFTtonQ2Fva73n/5Z3Gwgng==',

    ];
   

    public function index()
    {

        $d = input('post.');

        if($this->is_weixin()){
            $this->e_msg('微信中不能用支付宝支付');
        }

        // halt($d);
        $validate = validate('Pay');
        $res = $validate->scene('pay')->check($d);
        if (!$res) {
            $this->e_msg($validate->getError());
        }


        
        /*
            必要参数   
                用户id   $us_id
                订单编号   $orderid
                金额       $num
                类型       $type (订单类型) 1领取礼包
                作用       $relevance         (礼包id) 
         */
        
        $us_id = $d['us_id'];
        $orderid = "sp" . date("YmdHis") . rand(100, 999);
        $num = $d['num'];
        $type = $d['type'];
        $relevance = $d['relevance'];

        $uu = Db::name('user')->where('id',$us_id)->field('id,us_account')->find();
        if(!$uu){
            $this->error('该用户不存在');
        }
        $rel = model('AlipayPay')->tianjia($us_id, $orderid, $num, $type, $relevance);
        // if ($rel) {
        //     $rst = model('AlipayPay')->back_success($orderid);
        //     if ($rst) {
        //         $this->s_msg('支付成功');
        //     }
        // }
        /*
            支付代码

         */

        $order = [
            'out_trade_no' => $orderid,
            'total_amount' => '0.01',
            'subject'      => '手机支付',
        ];
        $result = Pay::alipay($this->config)->wap($order);
        $brr = [
            'code'=>1,
            'data' => $result,
        ];
        $this->msg($brr);
        // return Pay::alipay($this->config)->wap($order)->send();

        //扫码支付
        // $result =  $wechat->scan($order);
        // $url = $result->code_url;

        // $this->assign('url2',$url);
        // return $this->fetch();
    }

    public function notify()
    {
        $pay = Pay::alipay($this->config);
        try{
            // return $alipay->success()->send();
            $data = $pay->verify(); // 是的，验签就这么简单！
            $arr = $data->all();
            lllog::write($arr,'notice');
            if($arr['trade_status' ]=='TRADE_SUCCESS'){
                 model('AlipayPay')->back_success($arr['out_trade_no']);
            }
            // Log::debug('Alipay notify', $data->all());
        } catch (Exception $e) {
            // $e->getMessage();
        }
        
        // return $pay->success()->send();// laravel 框架中请直接 `return $pay->success()`
    }
    protected function is_weixin() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
}