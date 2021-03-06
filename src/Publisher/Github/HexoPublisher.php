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
     * @return void
     */
    public function publish()
    {
        $cmds = [];

        // cd到项目根目录 并使用hexo new命令构建新文章
        $cmds[] = 'cd ' . static::PROJECT_LOCAL_DIR . '';
        $cmds[] = 'hexo new ' . escapeshellarg($this->_article->getTitle()) . '';

        // 执行
        $cmd = implode(' && ', $cmds);
        $result = [];
        $status = -1;
        exec($cmd, $result, $status);

        // 获取实际生成的文件名
        $fileName = explode('/', $result[0]);
        $fileName = static::PROJECT_LOCAL_DIR . '/source/_posts/' . $fileName[count($fileName) - 1];

        if ($status !== 0) {
            unlink($fileName);// 失败时删除文件
            log(PHP_EOL . implode(PHP_EOL, $result) . PHP_EOL);
            throw new MultiArticleException('exec command fail: ' . $cmd);
        }

        // 构建页面内容
        $page = $this->buildPage();
        $status = file_put_contents($fileName, $page);
        if (!$status) {
            unlink($fileName);// 失败时删除文件
            throw new MultiArticleException('put content to page fail: ' . $fileName);
        }

        // 发布文件
        $cmds = [];
        $cmds[] = 'cd ' . static::PROJECT_LOCAL_DIR . '';
        $cmds[] = 'hexo deploy -g';

        // 执行
        $cmd = implode(' && ', $cmds);
        $result = [];
        $status = -1;
        exec($cmd, $result, $status);
        if ($status !== 0) {
            log(PHP_EOL . implode(PHP_EOL, $result) . PHP_EOL);
            unlink($fileName);// 失败时删除文件
            throw new MultiArticleException('exec command fail: ' . $cmd);
        }

    }

    protected function buildPage()
    {
        // 构建头部
        date_default_timezone_set('PRC');
        $header = [
            'title'   => $this->_article->getTitle(),
            'date'    => date('Y/m/d H:i:s'),
            'updated' => date('Y/m/d H:i:s'),
            'tags'    => $this->_article->getKeys(),
            'author'  => $this->_article->getAuthor(),
        ];
        $headerStr = '---' . PHP_EOL;
        foreach ($header as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $headerStr .= $key . ': ' . (is_array($value) ? PHP_EOL . ' - ' . implode(PHP_EOL . ' - ', $value) : $value) . PHP_EOL;
        }
        $headerStr .= '---';

        // 构建内容
        $content = $this->_article
            ->toType(ARTICLE_CONTENT_TYPE_MD)
            ->getContent();

        // 最终page
        $page = $headerStr . PHP_EOL . $content;

        return $page;
    }

}