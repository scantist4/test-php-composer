<?php
/********************************************
 *                Easy PHP                  *
 *                                          *
 * A lightweight PHP framework for studying *
 *                                          *
 *                 TIERGB                   *
 *      <https://github.com/TIGERB>         *
 *                                          *
 ********************************************/

namespace Framework;

use Framework\Container;
use Closure;

/**
 * Application
 *
 * 框架应用类
 *
 * 整个框架自身就是一个应用
 *
 * @author TIERGB <https://github.com/TIGERB>
 */
class App
{
    /**
    　*　框架加载流程一系列处理类集合
    　*
    　* @var array
    　*/
    private $_handlesList = [];

    /**
     * 框架实例
     *
     * @var object
     */
    public static $app;

    /**
     * 服务容器
     *
     * @var object
     */
    private $_container;

    /**
     * 请求对象
     *
     * @var object
     */
    private $_request;

    /**
     * 响应对象
     *
     * @var object
     */
    private $_responseData;

    /**
     * 构造函数
     */
    public function __construct()
    {
        self::$app = $this;
        $this->container = new Container();
    }

    /**
     * 魔法函数__get
     *
     * @param  string $name  属性名称
     * @return mixed
     */
    public function __get($name = '')
    {
        $name = '_' . $name;
        return $this->$name;
    }

    /**
     * 魔法函数__set
     *
     * @param  string $name   属性名称
     * @param  mixed  $value  属性值
     * @return mixed
     */
    public function __set($name = '', $value = '')
    {
        $name = '_' . $name;
        $this->$name = $value;
    }

    /**
     * 注册框架运行过程中一系列处理类
     *
     * @param  object $handle handle类
     * @return void
     */
    public function load(Closure $handle)
    {
        $this->_handlesList[] = $handle;
    }

    /**
     * 运行应用
     *
     * @param  Request $request 请求对象
     * @return void
     */
    public function run(Closure $request)
    {
        $this->request = $request();
        foreach ($this->_handlesList as $handle) {
            $handle()->register($this);
        }
    }

    /**
     * 生命周期结束
     *
     * 响应请求
     * @param  Closure $closure 响应类
     * @return json
     */
    public function response(Closure $closure)
    {
        // $closure()->reponse($this->responseData);
        $closure()->restSuccess($this->responseData);
    }

}