<?php
/**
 * Created by PhpStorm.
 * User: funco
 * Date: 2018/10/25
 * Time: 2:04 PM
 */

namespace EFrame\MultiArticle\Publisher\Lx;

use EFrame\MultiArticle\Article\ArticleInterface;
use EFrame\MultiArticle\Publisher\Config;
use EFrame\MultiArticle\Publisher\Publisher;

abstract class LxPublisher extends Publisher
{
    use Api;

    /**
     * LxPublisher constructor.
     *
     * @param ArticleInterface|null $article
     * @param Config|null           $configs
     * @throws \EFrame\MultiArticle\Exception\MultiArticleException
     */
    public function __construct(ArticleInterface $article = null, Config $configs = null)
    {
        $this->initConfigDefine();
        parent::__construct($article, $configs);

        $this->initStaffIdOption();
    }

    public function publish()
    {
        $attributes = [
            'title'       => $this->_article->getTitle(),
            'content'     => $this->_article->toType(ARTICLE_CONTENT_TYPE_MD)->getContent(),
            'is_markdown' => 1,
        ];
        $options = [
            'privilege_type' => $this->getConfig('privilegeType'),
            'source'         => $this->getConfig('source'),
            'reship_url'     => $this->getConfig('reshipUrl'),
        ];
        /*$response = */
        $this->postDoc($this->getConfig('staffId'), $attributes, $options);
//        return $response;
    }

//    /**
//     * 自行在子类中实现getToken，便于实现缓存方案
//     *
//     * @return string
//     */
//    abstract public function getAccessToken();

    /**
     * 初始化人员列表
     */
    protected function initStaffIdOption()
    {
        $page = 1;
        $lastPage = 1;
        $data = [
            'per_page' => 100,
        ];
        while ($page <= $lastPage) {
            // 获取数据
            $data['page'] = $page;
            $response = $this->get('staffs', $data);

            // 更新信息
            $staffs = $response['data'];
            $lastPage = $response['meta']['last_page'];

            // 添加用户列表
            foreach ($staffs as $staff) {
                $this->_configDefine['staffId']['options'][] = [
                    'name'  => $staff['attributes']['english_name'] ?? $staff['attributes']['name'],
                    'value' => $staff['id'],
                ];
            }

            $page = $response['meta']['current_page'] + 1;
        }
    }

    /**
     * @throws \EFrame\MultiArticle\Exception\MultiArticleException
     */
    protected function initConfigDefine()
    {
        $this->setConfigDefine([
            'staffId'       => [ // key必须与元素内字段key保持一致
                'name'    => '乐享id',// 任意名称，仅作展示用途
                'key'     => 'staffId',// 配置项对应key
                'type'    => PUBLISHER_CONFIG_TYPE_TXT | PUBLISHER_CONFIG_VALUE_SINGLE,// 配置项输入类型字符
                'options' => [],
            ],
            'privilegeType' => [
                'name'    => '公开权限',
                'key'     => 'privilegeType',// 配置项对应key
                'type'    => PUBLISHER_CONFIG_TYPE_INT | PUBLISHER_CONFIG_VALUE_SINGLE,// 配置项输入类型字符
                'options' => [
                    [
                        'name'  => '公开',
                        'value' => 0,
                    ],
                    [
                        'name'  => '部分人可见',
                        'value' => 1,
                    ],
                    [
                        'name'  => '仅创建者可见',
                        'value' => 2,
                    ],
                ],
            ],
            'source'        => [
                'name'    => '来源',
                'key'     => 'privilegeType',// 配置项对应key
                'type'    => PUBLISHER_CONFIG_TYPE_INT | PUBLISHER_CONFIG_VALUE_SINGLE,// 配置项输入类型字符
                'options' => [
                    [
                        'name'  => '原创',
                        'value' => 'original',
                    ],
                    [
                        'name'  => '转载',
                        'value' => 'reship',
                    ],
                ],
            ],
            'reshipUrl'     => [
                'name' => '来源',
                'key'  => 'reshipUrl',// 配置项对应key
                'type' => PUBLISHER_CONFIG_TYPE_INT | PUBLISHER_CONFIG_VALUE_SINGLE,// 配置项输入类型字符
            ],
        ]);
    }
}