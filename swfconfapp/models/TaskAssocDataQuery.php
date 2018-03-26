<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TaskAssocData]].
 *
 * @see TaskAssocData
 */
class TaskAssocDataQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TaskAssocData[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TaskAssocData|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
