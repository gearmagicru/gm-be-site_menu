<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */


namespace Gm\Backend\SiteMenu\Model;

use Gm;
use Gm\Helper\Url;
use Gm\Mvc\Module\BaseModule;
use Gm\Site\Data\Model\Article;
use Gm\Site\Data\Model\ArticleCategory;
use Gm\Panel\Data\Model\AdjacencyFormModel;

/**
 * Модель данных профиля пункта главного меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class ItemForm extends AdjacencyFormModel
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\SiteMenu\Module
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => false,
            'tableName'  => '{{menu_items}}',
            'primaryKey' => 'id',
            'parentKey'  => 'parent_id',
            'countKey'   => 'count',
            'fields'     => [
                ['id'],
                [
                    'parent_id', 
                    'alias' => 'parentId'
                ],
                [
                    'link_id', 
                    'alias' => 'linkId'
                ],
                [
                    'name', 
                    'label' => 'Name'
                ],
                [ // язык
                    'language_id',
                    'alias' => 'language', 
                    'label' => 'Language'
                ],
                [
                    'description',
                    'title' => 'Description'
                ],
                [
                    'title',
                    'label' => 'Title'
                ],
                [
                    'css',
                    'label' => 'Class CSS'
                ],
                [
                    'rel',
                    'label' => 'Relationship to link'
                ],
                [
                    'type',
                    'label' => 'Type'
                ],
                [
                    'external_url',
                    'alias' => 'externalUrl',
                    'label' => 'Open in new window'
                ],
                [
                    'image_url',
                    'alias' => 'imageUrl',
                    'label' => 'Icon'
                ],
                [
                    'url',
                    'label' => 'URL address'
                ],
                [
                    'visible', 
                    'label' => 'Visible'
                ]
            ],
            // правила форматирования полей
            'formatterRules' => [
                [['name', 'description', 'title', 'css', 'externalUrl', 'imageUrl'], 'safe'],
                [['visible', 'externalUrl'], 'logic'],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);

                /** @var int $menuId Идентификатор меню */
                $menuId = $this->getMenuId();
                if ($menuId) {
                    $response
                        ->meta
                            ->cmdReloadTreeGrid($this->module->viewId('items' . $menuId . '-grid'));
                }
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);

                /** @var int $menuId Идентификатор меню */
                $menuId = $this->getMenuId();
                if ($menuId) {
                    $response
                        ->meta
                            ->cmdReloadTreeGrid($this->module->viewId('items' . $menuId . '-grid'));
                }
            });
    }

    /**
     * Выполняет проверку выбранной статьи.
     * 
     * @return bool
     */
    protected function validateArticle(): bool
    {
        /** @var null|int $articleId */
        $articleId = $this->getUnsafeAttribute('articleId');
        if ($articleId === null) {
            $this->setError($this->errorFormatMsg(Gm::t('app', "Value is required and can't be empty"), 'Article'));
            return false;
        }

        /** @var null|Article $article */
        $article = new Article();
        /** @var null|Article $article */
        $article = $article->getById($articleId);
        if ($article === null) {
            $this->setError(Gm::t('app', 'Could not defined data model "{0}"', ['Article']));
            return false;
        }
        $this->linkId = $articleId;
        $this->url = $article->getUrl(['local' => true]);
        return true;
    }

    /**
     * Выполняет проверку выбранной категории статьи.
     * 
     * @return bool
     */
    protected function validateCategory(): bool
    {
        /** @var null|int $categoryId */
        $categoryId = $this->getUnsafeAttribute('categoryId');
        if ($categoryId === null) {
            $this->setError($this->errorFormatMsg(Gm::t('app', "Value is required and can't be empty"), 'Article category'));
            return false;
        }

        /** @var null|ArticleCategory $category */
        $category = (new ArticleCategory())->getById($categoryId);
        if ($category === null) {
            $this->setError(Gm::t('app', 'Could not defined data model "{0}"', ['Article category']));
            return false;
        }
        $this->linkId = $categoryId;
        $this->url = $category->getUrl(['local' => true]);
        return true;
    }

    /**
     * Выполняет проверку выбранной произвольной ссылки.
     * 
     * @return bool
     */
    protected function validateLink(): bool
    {
        /** @var null|string $linkUrl */
        $linkUrl = $this->getUnsafeAttribute('linkUrl');
        if (empty($linkUrl)) {
            $this->setError($this->errorFormatMsg(Gm::t('app', "Value is required and can't be empty"), 'URL address'));
            return false;
        }
        $this->linkId = null;
        $this->url = $linkUrl;
        return true;
    }

    /**
     * Выполняет проверку выбранного URL адреса файла.
     * 
     * @return bool
     */
    protected function validateFile(): bool
    {
        /** @var null|string $fileUrl */
        $fileUrl = $this->getUnsafeAttribute('fileUrl');
        if (empty($fileUrl)) {
            $this->setError($this->errorFormatMsg(Gm::t('app', "Value is required and can't be empty"), 'File URL'));
            return false;
        }
        $this->linkId = null;
        $this->url = Url::to([$fileUrl, 'local' => true]); 
        return true;
    }

    /**
     * Выполняет проверку выбранного якаря.
     * 
     * @return bool
     */
    protected function validateAnchor(): bool
    {
        /** @var null|string $anchor */
        $anchor = $this->getUnsafeAttribute('anchor');
        if (empty($anchor)) {
            $this->setError($this->errorFormatMsg(Gm::t('app', "Value is required and can't be empty"), 'Anchor'));
            return false;
        }
        $this->linkId = null;
        // !== '/'
        if (strncmp($anchor, '/', 1) !== 0) {
            $this->url = '/' . $anchor;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        if ($isValid) {
            // тип
            switch ($this->type) {
                // статья
                case 'article':
                    if (!$this->validateArticle()) return false;
                    break;

                // категория статьи
                case 'category':
                    if (!$this->validateCategory()) return false;
                    break;

                // произвольная ссылка
                case 'link':
                    if (!$this->validateLink()) return false;
                    break;

                // файл на сервере
                case 'file':
                    if (!$this->validateFile()) return false;
                    break;

                // якорь
                case 'anchor':
                    if (!$this->validateAnchor()) return false;
                    break;

                default:
                    $this->setError($this->errorFormatMsg(Gm::t('app', "Value is required and can't be empty"), 'Type'));
                    return false;
            }
        }
        return $isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete(): bool
    {
        /** @var null|Item $item Выбранный пункт меню */
        $item = (new Item())->get($this->getIdentifier());
        if ($item) {
            // уменьшаем следующие индексы пунктов меню, чтобы был порядок
            $item->decrementIndexAfter();
        }

        return parent::beforeDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeInsert(array &$columns): void
    {
        /** @var int $menuId Идентификатор меню */
        $menuId = $this->getMenuId();
        if ($menuId) {
            $columns['menu_id'] = $menuId;
        }
        // получить следующий порядковый номер в списке
        $columns['index'] = (new Item())->getNextIndex($columns['parent_id']);
    }

    /**
     * Возвращает идентификатор меню.
     * 
     * @return int
     */
    protected function getMenuId(): int
    {
        return Gm::$app->request->getQuery('menu', 0, 'int');
    }

    /**
     * Возвращает значение для выпадающего списка статей.
     * 
     * @param null|int|string $itemId Идентификатор статьи.
     * 
     * @return array|null
     */
    protected function getArticleComboItem($itemId): ?array
    {
        if ($itemId) {
            /** @var \Gm\Backend\Articles\Model\Article|null $articleAR */
            $articleAR = Gm::$app->modules->getModel('Article', 'gm.be.articles');
            if ($articleAR) {
                /** @var \Gm\Backend\Articles\Model\Article|null $article */
                $article = $articleAR->getById($itemId);
                if ($article) {
                    return [
                        'type'  => 'combobox',
                        'value' => $article->id,
                        'text'  => $article->header
                    ];
                }
            }
        }
        return null;
    }

    /**
     * Возвращает значение для выпадающего списка категорий статей.
     * 
     * @param null|int|string $itemId Идентификатор категории статьи.
     * 
     * @return array|null
     */
    protected function getCategoryComboItem($itemId): ?array
    {
        if ($itemId) {
            /** @var \Gm\Backend\ArticleCategories\Model\Category|null $categoryAR */
            $categoryAR = Gm::$app->modules->getModel('Category', 'gm.be.article_categories');
            if ($categoryAR) {
                /** @var \Gm\Backend\ArticleCategories\Model\Category|null $category */
                $category = $categoryAR->getById($itemId);
                if ($category) {
                    return [
                        'type'  => 'combobox',
                        'value' => $category->id,
                        'text'  => $category->name
                    ];
                }
            }
        }
        return null;
    }

    /**
     * Устанавливает значение атрибуту "language".
     * 
     * @param null|string|int $value
     * 
     * @return void
     */
    public function setLanguage($value): void
    {
        $value = (int) $value;
        $this->attributes['language'] = $value === 0 ? null : $value;
    }

    /**
     * Возвращает значение атрибута "language" для элемента интерфейса формы.
     * 
     * @param null|string|int $value
     * 
     * @return array
     */
    public function outLanguage($value): array
    {
        $language = $value ? Gm::$app->language->available->getBy($value, 'code') : null;
        if ($language) {
            return [
                'type'  => 'combobox', 
                'value' => $language['code'],
                'text'  => $language['shortName'] . ' (' . $language['tag'] . ')'
            ];
        }
        return [
            'type'  => 'combobox',
            'value' => 0,
            'text'  => Gm::t(BACKEND, '[None]')
        ];       
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        switch ($this->type) {
            // статья
            case 'article':
                $this->addAttribute('articleId', $this->getArticleComboItem($this->linkId));
                break;

            // категория статьи
            case 'category':
                $this->addAttribute('categoryId', $this->getCategoryComboItem($this->linkId));
                break;

            // произвольная ссылка
            case 'link':
                $this->addAttribute('linkUrl', $this->url);
                break;

            // файл на сервере
            case 'file':
                $this->addAttribute('fileUrl', $this->url);
                break;

             // якорь
            case 'anchor':
                $this->addAttribute('anchor', $this->url);
                break;
        }
    }
}
