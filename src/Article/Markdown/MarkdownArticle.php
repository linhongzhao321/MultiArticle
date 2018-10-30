<?php
/**
 * Created by PhpStorm.
 * User: funco
 * Date: 2018/10/23
 * Time: 6:12 PM
 */

namespace EFrame\MultiArticle\Article\Markdown;

use EFrame\MultiArticle\Article\ArticleInterface;
use EFrame\MultiArticle\Article\Html\HtmlArticle;
use EFrame\MultiArticle\Parser\Parser;

define('ARTICLE_CONTENT_TYPE_TXT', 0);  // 纯文本
define('ARTICLE_CONTENT_TYPE_HTML', 1); // html
define('ARTICLE_CONTENT_TYPE_MD', 2);   // markdown
define('ARTICLE_CONTENT_TYPE_MAX', ARTICLE_CONTENT_TYPE_MD);   // content类型最大值

class MarkdownArticle implements ArticleInterface
{
    protected $title = '';
    protected $content = '';
    protected $statement = '';
    protected $author = '';
    protected $authorGroup = '';
    protected $intro = '';
    protected $cover = '';
    protected $keys = [];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatement(): string
    {
        return $this->statement;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getAuthorGroup(): string
    {
        return $this->authorGroup;
    }

    public function getIntroduction(): string
    {
        return $this->intro;
    }

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setTitle(string $title)
    {
        $this->title = trim($title);
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = trim($content);
    }

    public function setStatement(string $statement)
    {
        $this->statement = $statement;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function setAuthorGroup(string $group)
    {
        $this->authorGroup = $group;
    }

    public function setIntroduction(string $intro)
    {
        $this->intro = $intro;
    }

    public function setCover(string $cover)
    {
        $this->cover = $cover;
    }

    public function toType(int $type): ArticleInterface
    {
        $result = false;
        switch ($type) {
            case ARTICLE_CONTENT_TYPE_TXT:
                $result = $this->toTxt();
                break;
            case ARTICLE_CONTENT_TYPE_HTML:
                $result = $this->toHtml();
                break;
            case ARTICLE_CONTENT_TYPE_MD:
                $result = $this->toMarkdown();
                break;
        }
        return $result;
    }

    protected function toMarkdown()
    {
        return $this;
    }

    protected function toTxt()
    {

    }

    protected function toHtml()
    {
        $parser = new Parser();
        $htmlArticle = new HtmlArticle();
        $htmlArticle->setContent($parser->makeHtml($this->content));
        return $htmlArticle;
    }

    public function getKeys(): array
    {
        return $this->keys;
    }

    public function setKeys(array $keys)
    {
        $this->keys = $keys;
    }

    public function addKey(string $key)
    {
        $this->keys[] = $key;
    }

}