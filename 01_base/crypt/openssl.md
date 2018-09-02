
基础：

加密算法一般分为两种：对称加密算法 和 非对称加密算法。


对称加密
对称加密算法是消息发送者和接收者使用同一个密匙，发送者使用密匙加密了文件，接收者使用同样的密匙解密，获取信息。常见的对称加密算法有：des/aes/3des.

对称加密算法的特点有：速度快，加密前后文件大小变化不大，但是密匙的保管是个大问题，因为消息发送方和接收方任意一方的密匙丢失，都会导致信息传输变得不安全。


非对称加密
与对称加密相对的是非对称加密，非对称加密的核心思想是使用一对相对的密匙，分为公匙和私匙，私匙自己安全保存，而将公匙公开。公钥与私钥是一对，如果用公钥对数据进行加密，只有用对应的私钥才能解密；如果用私钥对数据进行加密，那么只有用对应的公钥才能解密。发送数据前只需要使用接收方的公匙加密就行了。常见的非对称加密算法有RSA/DSA:

非对称加密虽然没有密匙保存问题，但其计算量大，加密速度很慢,有时候我们还需要对大块数据进行分块加密。


数字签名
为了保证数据的完整性，还需要通过散列函数计算得到一个散列值，这个散列值被称为数字签名。其特点有：
无论原始数据是多大，结果的长度相同的；
输入一样，输出也相同；
对输入的微小改变，会使结果产生很大的变化；
加密过程不可逆，无法通过散列值得到原来的数据；
常见的数字签名算法有md5,hash1等算法。





PHP的openssl扩展

openssl扩展使用openssl加密扩展包，封装了多个用于加密解密相关的PHP函数，极大地方便了对数据的加密解密。 常用的函数有：


对称加密相关：
string openssl_encrypt ( string $data , string $method , string $password)

其中$data为其要加密的数据，$method是加密要使用的方法，$password是要使用的密匙，函数返回加密后的数据；

其中$method列表可以使用openssl_get_cipher_methods()来获取，我们选取其中一个使用，$method列表形如：

Array(
    0 => aes-128-cbc,   // aes加密
    1 => des-ecb,       // des加密
    2 => des-ede3,      // 3des加密
    ...
    )
其解密函数为 string openssl_encrypt ( string $data , string $method , string $password)


非对称加密相关：
openssl_get_publickey();openssl_pkey_get_public();      // 从证书导出公匙；
openssl_get_privatekey();openssl_pkey_get_private();    // 从证书导出私匙；
它们都只需要传入证书文件（一般是.pem文件）；

openssl_public_encrypt(string $data , string &$crypted , mixed $key [, int $padding = OPENSSL\_PKCS1\_PADDING ] )
使用公匙加密数据,其中$data是要加密的数据；$crypted是一个引用变量，加密后的数据会被放入这个变量中；$key是要传入的公匙数据；由于被加密数据分组时，有可能不会正好为加密位数bit的整数倍，所以需要$padding(填充补齐)，$padding的可选项有 OPENSSL_PKCS1_PADDING, OPENSSL_NO_PADDING,分别为PKCS1填充，或不使用填充；

与此方法相对的还有（传入参数一致）:

openssl_private_encrypt()；  // 使用私匙加密；
openssl_private_decrypt()；  // 使用私匙解密；
openssl_public_decrypt()；  // 使用公匙解密；


还有签名和验签函数：

bool openssl_sign ( string $data , string &$signature , mixed $priv_key_id [, mixed $signature_alg = OPENSSL_ALGO_SHA1 ] )
int openssl_verify ( string $data , string $signature , mixed $pub_key_id [, mixed $signature_alg = OPENSSL_ALGO_SHA1 ] )
签名函数：$data为要签名的数据；$signature为签名结果的引用变量；$priv_key_id为签名所使用的私匙;$signature_alg为签名要使用的算法，其算法列表可以使用openssl_get_md_methods ()得到，形如：

array(
    0 => MD5,
    1 => SHA1,
    2 => SHA256,
    ...
)
验签函数：与签名函数相对，只不过它要传入与私匙对应的公匙；其结果为签名验证结果，1为成功，0为失败，-1则表示错误；





加密实例
以下是一个非对称加密使用的小例子：

// 获取公匙
$pub_key = openssl_get_publickey('test.pem');

$encrypted = '';
// 对数据分块加密
for ($offset = 0, $length = strlen($raw_msg); $offset < $length; $offset += $key_size){    
    $encryptedBlock = '';
    $data = substr($raw_msg, $offset, $key_size)
    if (!openssl_public_encrypt($data, $encryptedBlock, $pub_key, OPENSSL_PKCS1_PADDING)){
       return '';
    } else {
        $encrypted .= $encryptedBlock;
 }
 return $encrypted;
而对称加密就非常简单了，直接使用ssl_encrypt()函数即可；

当然一些接口可能会对加密方法进行不同的要求，如不同的padding,加密块大小等等，这些就需要使用者自己调整了。

因为我们是在HTTP协议之上处理的数据，所以数据加密完成后，就可以直接发送了，不用再考虑底层的传输，使用cURL或SOAP扩展方法，就可以直接请求接口啦。







