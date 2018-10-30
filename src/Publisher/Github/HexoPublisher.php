<?php
/**
 * Created by PhpStorm.
 * User: funco
 * Date: 2018/10/30
 * Time: 9:25 AM
 */

namespace EFrame\MultiArticle\Publisher\Github;


use EFrame\MultiArticle\Exception\MultiArticleException;

class HexoPublisher extends GithubPublisher
{

    /**
     * @throws MultiArticleException
     */
    public function publish()
    {
        $fileName = self::PROJECT_LOCAL_DIR . '/source/_posts/' . trim($this->_article->getTitle()) . '.md';
        if (file_exists($fileName)) {
            throw new MultiArticleException('文件已存在');
        }

        $cmds = [];

        // cd到项目根目录
        $cmds[] = 'cd ' . self::PROJECT_LOCAL_DIR . '';

        // 新建文章
        $cmds[] = 'hexo new \'' . escapeshellarg($this->_article->getTitle()) . '\'';

        // 执行
        $cmd = implode(' && ', $cmds);
        $result = [];
        $status = -1;
        exec($cmd, $result, $status);
        if ($status !== 0) {
            log(PHP_EOL . implode(PHP_EOL, $result) . PHP_EOL);
            throw new MultiArticleException('exec command fail: ' . $cmd);
        }

        // 构建页面内容
        $page = $this->buildPage();
        $status = file_put_contents($fileName, $page);
        if (!$status) {
            throw new MultiArticleException('put content to page fail: ' . $fileName);
        }

        // 发布文件
        $cmds = [];
        $cmds[] = 'cd ' . self::PROJECT_LOCAL_DIR . '';
        $cmds[] = 'hexo deploy -g';

        // 执行
        $cmd = implode(' && ', $cmds);
        $result = [];
        $status = -1;
        exec($cmd, $result, $status);
        if ($status !== 0) {
            log(PHP_EOL . implode(PHP_EOL, $result) . PHP_EOL);
            throw new MultiArticleException('exec command fail: ' . $cmd);
        }

    }

    protected function buildPage()
    {
        // 构建头部
        date_default_timezone_set('PRC');
        $header = [
            'title' => $this->_article->getTitle(),
            'date'  => date('Y-m-f H:i:s'),
            'tags'  => $this->_article->getKeys(),
        ];
        $headerStr = ';;;' . PHP_EOL;
        foreach ($header as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $headerStr .= '"' . $key . '": "' . \GuzzleHttp\json_encode($value) . ',' . PHP_EOL;
        }
        $headerStr .= '"blank": ' . PHP_EOL;
        $headerStr .= ';;;';

        // 构建内容
        $content = $this->_article
            ->toType(ARTICLE_CONTENT_TYPE_MD)
            ->getContent();

        // 最终page
        $page = $headerStr . PHP_EOL . $content;

        return $page;
    }

}