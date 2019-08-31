<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Pay;
use DB;
class PayController extends Controller
{
    public $app_id;
    public $gate_way;
    public $notify_url;
    public $return_url;
    public $rsaPrivateKeyFilePath = '';  //路径
    public $aliPubKey = '';  //路径
    public $privateKey = 'MIIEpAIBAAKCAQEAnUriH3gSLS/dm+eTbWPYY05uxAX+ASkP+39KYtqhs45ZtkZjVGC2AxfFGJgzrYFJjg4RmV4jDPgGaL/9Q+yis4uA4a+y60Uk84wsr8Pj9fIw6ja2ixKhBJgACsOGHf93auq6kp+7UrMvHR9s7fNb+u9HVsWq2PjCyR+oqSPxMXlUrDAKrsAmKYAhLfqGSjIMNWmssizhD5kig4Dr0hTHdcfkb3x31mCswVz8tu7t2me5exDNXfiBMAmPogdKbGFkSepVWEsPT+JHSby2/hBTXb3ygcTBt8InKVi1TetOdMR38wJCZuIb5xE/jSIgK3qSuShtlQD+CT+T6mt163rDtQIDAQABAoIBAHbGwmSDFi67M3x0bfav7AppgulRoRKdQG1pHPHzKSJe/03Ob1mbQjapr3M4E+YUeEfmRdHrrUowzR4yxHyTZ/VvBd7m/5P7/cBP/LqpkS37OobS1BvG8IJ4iaeXKGLks/ev0z2/kwQLPSufvHEfUTj3kYZr8+yuROD9oC5BbvQs/MpeSUGZ813pX2K0YIQVAN6GJuilXRNQcPt/L1MAjZGd7aHEDWhhswSvIR5pwTmLRKkatO/Wr9Ealal6AgQmweYdXAetV3Or3HrMRJtaTzUcEn3cMMqDO9CSgRziXu1oep4cSApA8HsaqVyayLE/7lV3KX3CGEH1NWZE/nIH5iECgYEA0AMUJBSTHstwv43jo8IekWrg/0J5JakNbRd1kxyS3pQxYGo8zC7BNrsSrFSfOOGcPSei+lHSBYzmFeIB7n/HwiKTph3sGEJ+qbMLExReseBdo51OV9ZwIo+nX2m1LRRfmqlYR12xrlgxOzOTLdR7yUsoi3dN/L9OLTtPtMKy710CgYEAwZRgMJMnvgWllERuvrEJju/N5gdOmpsz7cPmQLix0ApLvEbqKtUnZuMECAH0/1XfdP/KzSHOTtysnq3JzSMCb3h38KoiTVs5P7/bEAB7FH2iJoYTLIgP/BDuSf3ptRlnlyKtNn7HZO9JRaaWN9jzMQEChYr1Ho6Acc2fAdSR2DkCgYAgWvo9Cn4/4gEtqpJoHH/Iusk4q0xQ0VTnTSjasy0dNgvgJWZDlFo1ey/SYm9J174HvSyapzN17Y60hK0sPfACgIJa6niY5W7yUok8dISuQmoOUx+mhhJ3LcUpEDZARtUAJ6s1nptOtSUjQkh4bn66ttgdqXcWA2PToVAqm8ZdeQKBgQC0GgX8a4z4QGyzK5AVnsUT1YytTXMWaPvBZRfec3gL5OhdvWS0gShtkxz3DksRHKYQRsQ6Yg9+U9XOtEYis0XazCxbHw9XBV6YLznzA19/yvuH+AnyzoyAqofpE3HS6lX0yXB6WS3OfMqhIMJ2J0Tr/NKJyKURzuc1+mbL3yfqEQKBgQC5lwYK2VzSHoWFP9SB/iBAC/NY7wJDiU7x9mNgrP12fg7DjSk2Kn8YY4GuqFruWqM51ECCv0sAs9kwcx0yjw2apQD0+HxsGiuPGekSvudJr1sGtOLs3ofbuAAsgp97npuBJ/FYYBwvU/LuIzBFyewpR7oTtXzEhfVr+anx6mroQQ==';
    public $publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnEwf3IqaCvj8FOx+/pIpg9wE6Q6mQRBnkYSP34Ni+l1+jOuKbW+RdCQ9uFIBAIwYjxjzNOYwJd0FZngHsq3635uxyjI9/yNJLYqFLk/4jPMbK2F8FreTz8Cjd+OcJwsL6jyv7BAByhyjh1MEXJx7bskNFSr8tFSrmJQQDiqyKXwUacKoyJTMj5L7vc7ID6nDO7Hk6TKU4i0t+7M8e+BEnFS9bFWx1+U2nzMxNPpKVsaHmntir2KGBriLVX1m1s+MJ3AD7Xkcd5YBXmq5X/PMZ4Vwm+d+xxOsRY8NSudyjI6T+BghtqJwYWvF7gGtoUklCoppRA753Q7MjvZME8AA1wIDAQAB';
    public function __construct()
    {
        $this->app_id = '2016093000632276';
        $this->gate_way = 'https://openapi.alipaydev.com/gateway.do';
        $this->notify_url = env('APP_URL').'/notify_url';
        $this->return_url = env('APP_URL').'/return_url';
    }
    public function do_pay(){
        //生成订单信息
        //货物信息
        DB::connection('mysql_shop')->beginTransaction(); //开启事务
//        $order_info = DB::connection('mysql_index')
//            ->table('cart')
//            ->where(['user_name'=>session('user_name')])
//            ->get()
//            ->toArray();

        $order_info = DB::connection('mysql_index')
            ->table('order')
            ->where(['order.user_name'=>session('user_name')])
            ->join('goods', 'order.goods_id', '=', 'goods.goods_id')
            ->join('cart', 'order.goods_id', '=', 'cart.goods_id')
            ->select('order.*', 'goods.*','cart.*')
            ->get()
            ->toArray();

        if(empty($order_info)){
            echo "购物车为空！";
            die();
        }
        $order_info=array_map('get_object_vars',$order_info);
        $total = 0;
        foreach($order_info as $v){
            $total += $v['goods_price'];
        }
        $oid = time().mt_rand(1000,1111);  //订单编号
        $order_result = DB::connection('mysql_index')->table('order')->insert([
            'order_num'=>$oid,
            'user_name'=>session('user_name'),
            'goods_id'=>$v['goods_id'],
            'total_price'=>$total,
            'add_time'=>time()
        ]);
        $order_detali_result = true;
//        dd($order_info);
        foreach($order_info as $v){
            $detail_result = DB::connection('mysql_index')->table('order_detail')->insert([
                'order_num'=>$oid,
                'goods_id'=>$v['goods_id'],
                'goods_name'=>$v['goods_name'],
                'user_name'=>$v['user_name'],
                'goods_picture'=>$v['goods_picture'],
                'buy_number'=>$v['buy_number'],
                'add_time'=>time()
            ]);
            if(!$detail_result){
                $order_detali_result = false;
                break;
            }
        }
        if(!$order_detali_result || !$order_result){
            DB::connection('mysql_shop')->rollBack();
            die('操作失败!');
        }
        DB::connection('mysql_shop')->commit();
        $this->ali_pay($oid);
    }
    public function notify_url(){

        $post_json = file_get_contents("php://input");
        \Log::Info($post_json);
        $post = json_decode($post_json,1);
        //业务处理
        if($post['trade_status'] == 'TRADE_SUCCESS'){
            //修改订单状态
            //清理购物车
        }
    }

    public function rsaSign($params) {
        return $this->sign($this->getSignContent($params));
    }
    protected function sign($data) {
        if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
            $priKey=$this->privateKey;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        }else{
            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
        }

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, 'UTF-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 根据订单号支付
     * [ali_pay description]
     * @param  [type] $oid [description]
     * @return [type]      [description]
     */
    public function ali_pay($oid){
        $order = DB::connection('mysql_index')->table('order')->where(['order_num'=>$oid,'status'=>1])->select(['total_price'])->first();
        if(empty($order)){
            echo "订单不存在!";
            die();
        }
        $order_info = $order;
        //业务参数
        $bizcont = [
            'subject'           => 'Lening-Order: ' .$oid,
            'out_trade_no'      => $oid,
            'total_amount'      => $order_info->total_price,
            'product_code'      => 'FAST_INSTANT_TRADE_PAY',
        ];
        //公共参数
        $data = [
            'app_id'   => $this->app_id,
            'method'   => 'alipay.trade.page.pay',
            'format'   => 'JSON',
            'charset'   => 'utf-8',
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'   => '1.0',
            'notify_url'   => $this->notify_url,        //异步通知地址
            'return_url'   => $this->return_url,        // 同步通知地址
            'biz_content'   => json_encode($bizcont),
        ];
        //签名
        $sign = $this->rsaSign($data);
        $data['sign'] = $sign;
        $param_str = '?';
        foreach($data as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $url = rtrim($param_str,'&');
        $url = $this->gate_way . $url;

        header("Location:".$url);
    }
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = 'UTF-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }
    /**
     * 支付宝同步通知回调
     */
    public function aliReturn()
    {
        header('Refresh:2;url=/order_list');
        echo "<h2>订单： ".$_GET['out_trade_no'] . ' 支付成功，正在跳转</h2>';
    }
    /**
     * 支付宝异步通知
     */
    public function aliNotify()
    {
        $data = json_encode($_POST);
        $log_str = '>>>> '.date('Y-m-d H:i:s') . $data . "<<<<\n\n";
        //记录日志
        file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
        //验签
        $res = $this->verify($_POST);
        $log_str = '>>>> ' . date('Y-m-d H:i:s');
        if($res){
            //记录日志 验签失败
            $log_str .= " Sign Failed!<<<<< \n\n";
            file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
        }else{
            $log_str .= " Sign OK!<<<<< \n\n";
            file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
            //验证订单交易状态
            if($_POST['trade_status']=='TRADE_SUCCESS'){

            }
        }

        echo 'success';
    }
    //验签
    function verify($params) {
        $sign = $params['sign'];
        if($this->checkEmpty($this->aliPubKey)){
            $pubKey= $this->publicKey;
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        }else {
            //读取公钥文件
            $pubKey = file_get_contents($this->aliPubKey);
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
        }


        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($this->getSignContent($params), base64_decode($sign), $res, OPENSSL_ALGO_SHA256);

        if(!$this->checkEmpty($this->aliPubKey)){
            openssl_free_key($res);
        }
        return $result;
    }
}