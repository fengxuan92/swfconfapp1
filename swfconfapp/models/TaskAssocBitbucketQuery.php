<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TaskAssocBitbucket]].
 *
 * @see TaskAssocBitbucket
 */
class TaskAssocBitbucketQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TaskAssocBitbucket[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TaskAssocBitbucket|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
