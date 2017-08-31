<?php

namespace bttree\smytag\behaviors;

use bttree\smytag\models\Tag;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 *
 * ```php
 *
 * public $tags = [];
 *
 * public function rules()
 * {
 *  return [
 *    ...
 *      [['tags'], 'safe']
 *    ...
 *   ],
 *   ];
 * }
 *
 * public function behaviors()
 * {
 *     return [
 *         'smytag' => [
 *             'class'     => TagBehavior::className(),
 *             'attribute' => 'tags',
 *         ],
 *     ];
 * }
 *
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
    public $attribute;

    /**
     * @var string
     */
    protected $ownerClass;

    /**
     * @var integer
     */
    protected $ownerId;

    /**
     * @param \yii\base\Component $owner
     */
    public function attach($owner)
    {
        parent::attach($owner);

        $this->ownerClass = get_class($this->owner);
        $this->ownerId    = $this->owner->id;
    }

    public function updateTags()
    {
        $tags = $this->owner->{$this->attribute};

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


            Tag::deleteAll(['NOT IN', 'id', $tagIds]);
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
     * @return Tag[]
     */
    public function getTagsArray()
    {
        $tags = $this->getTags();

        return ArrayHelper::map($tags, 'title', 'title');
    }

    /**
     * @return array
     */
    public function events()
    {
        return array_merge([
                               ActiveRecord::EVENT_BEFORE_INSERT => 'updateTags',
                               ActiveRecord::EVENT_BEFORE_UPDATE => 'updateTags',
                           ],
                           parent::events());
    }
}