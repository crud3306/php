
  
常见问题整理  
  
  
微信小程序对称解密代码中Mcrypt被PHP7.1报错的解决方案  
报错信息：mcrypt_module_open() is deprecated  
---------------
文件wxBizCrypt.php  
原代码：  
```php
public function decrypt( $aesCipher, $aesIV )
{

    try {

        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        mcrypt_generic_init($module, $this->key, $aesIV);

        //解密
        $decrypted = mdecrypt_generic($module, $aesCipher);
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
    } catch (Exception $e) {
        return array(ErrorCode::$IllegalBuffer, null);
    }


    try {
        //去除补位字符
        $pkc_encoder = new PKCS7Encoder;
        $result = $pkc_encoder->decode($decrypted);

    } catch (Exception $e) {
        //print $e;
        return array(ErrorCode::$IllegalBuffer, null);
    }
    return array(0, $result);
}
```
   
改成：  
```php
/**
 * 对密文进行解密
 * @param string $aesCipher 需要解密的密文
 * @param string $aesIV 解密的初始向量
 * @return string 解密得到的明文
 */
public function decrypt( $aesCipher, $aesIV )
{
    try {
        //解密
        // $decrypted = openssl_decrypt(base64_decode($aesCipher), 'aes-128-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($aesIV));
        // PS: 下面代码中，如果传过来的数据已经base64_decode了，则不需要再次进行base64的操作
        $decrypted = openssl_decrypt($aesCipher, 'aes-128-cbc', $this->key, OPENSSL_RAW_DATA, $aesIV);

        // var_dump(($aesCipher));
        // var_dump(($this->key));
        // var_dump(($aesIV));
    } catch (\Exception $e) {
        return false;
    }

    try {
        //去除补位字符
        $pkc_encoder = new PKCS7Encoder;
        $result = $pkc_encoder->decode($decrypted);
    } catch (\Exception $e) {
        //print $e;
        return false;
    }
    return [0, $result];
}
```
注意：需要安装openssl扩展！！   


  
多个微信小程序共用某公众号已开通的商户平台
---------------
登陆微信公众号，选择左侧微信小程序菜单，点击新增（然后在浮层中选择关联或注册新的小程序），然后按提示下一步即可。
登录商户平台，产品中心 - appid授权管理 -  新增授权申请单 - 填写要绑定的appid，确认提交。
然后登录小程序，选择左侧 微信支付 - MA授权 - 查看申请 - 确认绑定 即可。

  
  
xxx
---------------



