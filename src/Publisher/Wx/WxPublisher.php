<?php
/**
 * Created by PhpStorm.
 * User: funco
 * Date: 2018/11/7
 * Time: 10:50 AM
 */

namespace EFrame\MultiArticle\Publisher\Wx;


use EFrame\MultiArticle\Publisher\Publisher;

class WxPublisher extends Publisher
{
    public function publish()
    {
        // 提取所有文件资源
        $this->_article->getContent();

    }

}