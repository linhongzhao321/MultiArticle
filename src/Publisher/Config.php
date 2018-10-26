<?php
/**
 * Created by PhpStorm.
 * User: funco
 * Date: 2018/10/25
 * Time: 8:46 AM
 */

namespace EFrame\MultiArticle\Publisher;

use EFrame\MultiArticle\Exception\MultiArticleException;

define('PUBLISHER_CONFIG_TYPE', 0b111);        // 配置类型占位
define('PUBLISHER_CONFIG_TYPE_AUTO', 0b000);   // 自动，即传什么用什么
define('PUBLISHER_CONFIG_TYPE_TXT', 0b001);    // 文本
define('PUBLISHER_CONFIG_TYPE_DATE', 0b010);   // 日期
define('PUBLISHER_CONFIG_TYPE_INT', 0b011);    // 整型
define('PUBLISHER_CONFIG_TYPE_DOUBLE', 0b100); // 浮点
define('PUBLISHER_CONFIG_TYPE_BOOL', 0b101);   // 布尔

define('PUBLISHER_CONFIG_VALUE', 0b1000);         // 单项多项占位
define('PUBLISHER_CONFIG_VALUE_SINGLE', 0b0000);  // value是单个元素
define('PUBLISHER_CONFIG_VALUE_MULTI', 0b1000);   // value是数组

trait Config
{
    /**
     * @var array $_configDefine 参考注释示例格式定义，在use的类中应该重写
     */
    protected $_configDefine = [
//        'sex' => [ // key必须与元素内字段key保持一致
//            'name'    => '性别',// 任意名称，仅作展示用途
//            'key'     => 'sex',// 配置项对应key
//            'type'    => PUBLISHER_CONFIG_TYPE_INT | PUBLISHER_CONFIG_VALUE_MULTI,// 配置项输入类型字符
//            'options' => [// 所有可选项,PUBLISHER_CONFIG_VALUE_SINGLE则忽略该字段
//                [
//                    'name'  => '男',
//                    'value' => 1,
//                ],
//                [
//                    'name'  => '女',
//                    'value' => 2,
//                ],
//            ],
//        ],
    ];

    /**
     * @var array $_configs key-value
     */
    protected $_configs = [
//        'sex' => 1,
    ];

    /**
     * @param array $configDefine
     * @throws MultiArticleException
     */
    public function setConfigDefine(array $configDefine)
    {
        $this->_configDefine = $configDefine;
        $this->validateConfigDefine();
    }

    public function setConfig(string $key, $value)
    {
        $this->_configs[$key] = $value;
    }

    public function setConfigs(array $configs)
    {
        foreach ($configs as $key => $value) {
            $this->_configs[$key] = $value;
        }
    }

    /**
     * 获取配置定义
     *
     * @return array
     */
    public function getConfigDefine()
    {
        return $this->_configDefine;
    }

    /**
     * @param string $key
     * @return array|null
     */
    public function getConfig(string $key)
    {
        return $this->_configs[$key] ?? null;
    }

    public function getConfigs()
    {
        return $this->_configs;
    }

    /**
     * 格式判断，保障配置项格式合法
     *
     * @throws MultiArticleException
     */
    protected function validateConfigDefine()
    {
        foreach ($this->_configDefine as $key => $value) {

            // 格式判断
            $flag = $value['name'] ?? false &&
                $value['key'] ?? false &&
                $value['key'] === $key &&
                is_string($key) &&
                (!($value['type'] & PUBLISHER_CONFIG_VALUE_MULTI) || $value['options'] ?? false);
            if (!$flag) {
                throw new MultiArticleException('Define format is error, see EFrame\MultiArticle\Publisher\Config');
            }
        }
    }
}