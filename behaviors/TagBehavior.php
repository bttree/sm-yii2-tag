<?php

namespace bttree\smyii2tag\behaviors;

use bttree\smyii2tag\models\Tag;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 *
 * ```php
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => TagBehavior::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * Class ConstArrayBehavior
 * @package yii\behaviors
 */
class TagBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    protected $ownerClass;

    /**
     * @var integer
     */
    protected $ownerId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->ownerClass = get_class($this->owner);
        $this->ownerId    = $this->owner->id;

        parent::init();

    }

    public function updateTags()
    {
        $tags = $this->owner->tags;

        if (is_array($tags) && !empty($tags)) {
            array_map('mb_strtolower', $tags);

            $attributes = [
                'model_id'    => $this->owner->id,
                'model_class' => $this->ownerClass
            ];

            $tagIds = [];
            foreach ($tags as $id => $title) {
                $attributes['title'] = $title;

                $tag = Tag::find()
                          ->where($attributes)
                          ->andWhere(['title' => $title])
                          ->one();
                if (null === $tag) {
                    $tag = new Tag();
                    $tag->setAttributes($attributes);
                    $tag->title = $title;
                    $tag->save();
                }
                $tagIds[] = $tag->id;
            }

            Tag::deleteAll([
                               ['NOT IN', 'id', $tagIds],
                               $attributes
                           ]);
        }
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        $tags = Tag::find()->where([
                                       'model_id'    => $this->owner->id,
                                       'model_class' => $this->ownerClass
                                   ])->all();

        return $tags;
    }

    /**
     * @return array
     */
    public function events()
    {
        return array_merge([
                               ActiveRecord::EVENT_BEFORE_INSERT => ['updateTags'],
                               ActiveRecord::EVENT_BEFORE_UPDATE => ['updateTags'],
                           ],
                           parent::events());
    }
}