<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/4
 * Time: 14:19
 */

namespace Jdmm\Saber;

use Swlib\Saber;
/**
 * Class SaberJdmm
 * @package Jdmm\Saber
 */
class SaberJdmm
{
    const KEY_BEFORE = 'before';
    const KEY_AFTER  = 'after';

    /**
     * 请求对象的名称
     * @var string
     */
    private $name='default name';

    /**
     * 请求的域名/ip.要求带有http://
     * @var string
     */
    private $base_uri;

    /**
     * @var Saber
     */
    private $defaultClient;

    /**
     * 前置拦截器数组
     * @var array
     */
    private $before = [];

    /**
     * 后置拦截器数组
     * @var array
     */
    private $after = [];

    /**
     * 默认配置项
     * @var array
     */
    private $options = [];

    private function getDefaultClient(): Saber
    {
        return $this->defaultClient ?? $this->defaultClient = Saber::create();
    }

    private function initClient() {
        $options = array_merge($this->options, [
            'base_uri'  => $this->getBaseUri(),
            'use_pool'  => true,
            'before'    => $this->getBefore(),
            'after'     => $this->getAfter(),
        ]);
        return $this->getDefaultClient()->setOptions($options);
    }

    public function psr(array $options = []): Request
    {
        return $this->initClient()->psr($options);
    }

    /** @return \Swlib\Saber\Saber */
    public function wait(): Saber
    {
        return $this->initClient()->wait();
    }

    /******************************************************************************
     *                             Request Methods                                *
     ******************************************************************************/

    /**
     * Note: Swoole <=4 doesn't support use coroutine in magic methods now
     * To be on the safe side, we removed __call and __call  instead of handwriting
     */

    public function request(array $options = [])
    {
        return $this->initClient()->request($options);
    }

    public function get(string $uri, array $options = [])
    {
        return $this->initClient()->get($uri, $options);
    }

    public function post(string $uri, $data = null, array $options = [])
    {
        return $this->initClient()->post($uri, $data, $options);
    }

    public function delete(string $uri, array $options = [])
    {
        return $this->initClient()->delete($uri, $options);
    }

    public function put($uri, $data=null, $options=[]) {
        return $this->initClient()->put($uri, $data, $options);
    }

    public function head(string $uri, array $options = [])
    {
        return $this->initClient()->head($uri, $options);
    }

    public function options(string $uri, array $options = [])
    {
        return $this->initClient()->options($uri, $options);
    }


    /******************************************************************************
     *                             Global Options                                 *
     ******************************************************************************/

    public function default(array $options = null): ?array
    {
        if ($options === null) {
            return Saber::getDefaultOptions();
        } else {
            Saber::setDefaultOptions($options); //global
            $this->initClient()->setOptions($options);
        }

        return null;
    }

    public function exceptionReport(?int $level = null): ?int
    {
        if ($level === null) {
            return self::default()['exception_report'];
        } else {
            self::default(['exception_report' => $level]);
        }

        return null;
    }

    public function exceptionHandle(callable $handle): void
    {
        self::default(['exception_handle' => $handle]);
    }

    /**
     * get uri
     * @return string
     */
    public function getBaseUri() {
        return $this->base_uri;
    }

    /**
     * get url example aa/bb?a=b
     * @param $path
     * @param array $data
     * @return string
     */
    public function getUrl($path, $data=[]) {
        return $path . '?' . http_build_query($data);
    }

    /**
     * get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 设置前置拦截器
     * @param callable $fun
     * @return array
     */
    public function setBefore($fun, $key='before') {
        $this->before[$key] = $fun;
    }
    
    public function getBefore() {
        return $this->before;
    }

    /**
     * 设置后置拦截器
     * @param callable $fun
     * @return array
     */
    public function setAfter($fun, $key='after') {
        $this->after[$key] = $fun;
    }
    
    public function getAfter() {
        return $this->after;
    }
}