<?php
/**
 * Created by PhpStorm.
 * User: funco
 * Date: 2018/10/29
 * Time: 2:05 PM
 */

namespace EFrame\MultiArticle\Publisher\Github;

use EFrame\MultiArticle\Article\ArticleInterface;
use EFrame\MultiArticle\Publisher\Config;
use EFrame\MultiArticle\Publisher\Publisher;

abstract class GithubPublisher extends Publisher
{
    // todo 使用前请重新定义覆盖这两个const
    const PROJECT_GIT_HUB_URL = '';// git hub url
    const PROJECT_LOCAL_DIR = '';// 本地路径

    /**
     * GihubPublisher constructor.
     *
     * @param ArticleInterface|null $article
     * @param Config|null           $configs
     */
    public function __construct(ArticleInterface $article = null, Config $configs = null)
    {
//        $this->initConfigDefine();
        parent::__construct($article, $configs);
    }


//    /**
//     * @throws \EFrame\MultiArticle\Exception\MultiArticleException
//     */
//    protected function initConfigDefine()
//    {
//        $this->setConfigDefine([
//            'publishDir' => [ // 发布路径
//                'name'    => '发布路径',// 任意名称，仅作展示用途
//                'key'     => 'publishDir',// 配置项对应key
//                'type'    => PUBLISHER_CONFIG_TYPE_TXT | PUBLISHER_CONFIG_VALUE_SINGLE,// 配置项输入类型字符
//            ],
//        ]);
//    }
}