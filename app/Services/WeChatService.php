<?php


namespace App\Services;


use App\Helper\WXBizDataCrypt;
use App\Models\WeChatAuthModel;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class WeChatService
{
    public function getAppletAuthorization($code, $config)
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$config['appid']}&secret={$config['secret']}&js_code={$code}&grant_type=authorization_code";
        $client = new \GuzzleHttp\Client();
        $res = json_decode($client->get($url)->getBody()->getContents());
        if (isset($res->errcode)) throw new InternalErrorException($res->errmsg, $res->errcode);
        return $res;
    }

    public function getOpenPlatformAuthorization($code, $config)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$config['appid']}&secret={$config['secret']}&code={$code}&grant_type=authorization_code";
        $client = new \GuzzleHttp\Client();
        $res = json_decode($client->get($url)->getBody()->getContents());
        if (isset($res->errcode)) throw new InternalErrorException($res->errmsg, $res->errcode);
        return $res;
    }

    public function decryptData($appid, $encrypted_data, $iv, $session_key): \stdClass
    {
        $wxBizDataCrypt = new WXBizDataCrypt($appid, $session_key);
        $errCode = $wxBizDataCrypt->decryptData($encrypted_data, $iv, $data);
        if ($errCode) throw new InternalErrorException('Data decryption failed', $errCode);
        return json_decode($data);
    }

    public function getWxUserInfo($access_token, $openid)
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}";
        $res = json_decode($client->get($url)->getBody()->getContents());
        if (isset($res->errcode)) throw new InternalErrorException($res->errmsg, $res->errcode);
        return $res;
    }

    /**
     * @param int $user_id
     * @return WeChatAuthModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByUserId(int $user_id)
    {
        return WeChatAuthModel::query()->whereUserId($user_id)->first();
    }

    public function getByUnionid(string $unionid)
    {
        return WeChatAuthModel::query()->whereUnionid($unionid)->first();
    }
}