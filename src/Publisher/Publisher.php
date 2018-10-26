<?php
/**
 * Created by PhpStorm.
 * 统一定义发布工具通用逻辑
 * User: funco
 * Date: 2018/10/25
 * Time: 8:28 AM
 */

namespace EFrame\MultiArticle\Publisher;

use EFrame\MultiArticle\Article\ArticleInterface;

/**
 * Class Publisher
 *
 * @package EFrame\MultiArticle\Publisher
 */
trait Publisher
{
    use Config;

    protected $_article = null;

    public function __construct(ArticleInterface $article = null, Config $configs = null)
    {
        // 文章
        if ($article) {
            $this->_article = $article;
        }

        // 配置信息
        if ($configs) {
            $this->setConfigs($configs->getConfigs());
        }
    }

    abstract public function publish();

    public function setArticle(ArticleInterface $article)
    {
        $this->_article = $article;
    }
}