## http客户端

#### 简介
对`Swlib\Saber`组件的简易封装,使其能够被调用链监控.同时保证与原来用法尽量一致.

#### 安装
```cmd
composer require jdmmswoft/http-client
```

#### 配置
* bean.php
```php
"user" => [
        'class' => \Jdmm\Saber\SaberJdmm::class,
        'name'  => 'user', //名称.与请求无关.但是调用链监控时候会使用.
        'base_uri' => 'http://user.com', //因为Saber包的特殊处理原因.导致必须增加协议头.
        'options' => [
            //此处增加一些头部信息以及超时时间的选项.配置项参考  
            // https://github.com/swlib/saber#%E9%85%8D%E7%BD%AE%E5%8F%82%E6%95%B0%E8%A1%A8
        ],
    ],
```

#### 使用说明
* 方法使用基本兼容SaberGM类.只是将静态方法的调用改为了通过bean()实例化
* 支持通过注解注入.
* 强烈建议使用代理.好处多多...

#### 示例
* UserProxy.php
```php
namespace App\Proxy;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Jdmm\Saber\SaberJdmm;

/**
 * Class UserProxy
 * @package App\Proxy
 * @Bean()
 */
class UserProxy
{
    /**
     * @var SaberJdmm
     * @Inject("user")
     */
    private $user;
    
    public function getById($id) {
        //方式一
        $user = \bean('user');
        
        //方式二
        $user = $this->user;
        
        $path = '/user/getId';
        $data = [
            'id' => $id,
        ];
        $url = $user->getUrl($path, $data);
        $reponse = $localhost->get($url);
        return $reponse->getParsedJsonArray();
    }
}
```

