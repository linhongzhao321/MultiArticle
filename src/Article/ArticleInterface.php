<?php
/**
 * Created by PhpStorm.
 * 统一文章的交互方式
 * User: funco
 * Date: 2018/10/25
 * Time: 8:29 AM
 */

namespace EFrame\MultiArticle\Article;

interface ArticleInterface
{

    public function getTitle(): string;// 标题

    public function getContent(): string;// 正文

    public function getStatement(): string;// 尾注

    public function getAuthor(): string;// 作者

    public function getAuthorGroup(): string;// 作者组

    public function getIntroduction(): string;//简介

    public function getCover(): string; // 封面

    public function getKeys(): array; // 关键字

    public function setTitle(string $title);

    public function setContent(string $content);

    public function setStatement(string $statement);

    public function setAuthor(string $author);

    public function setAuthorGroup(string $group);// 作者组

    public function setIntroduction(string $intro);//简介

    // cover使用url
    public function setCover(string $cover);

    public function setKeys(array $keys);

    public function addKey(string $key);

    public function toType(int $type): ArticleInterface;

}