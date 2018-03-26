<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CwdUser]].
 *
 * @see CwdUser
 */
class CwdUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CwdUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CwdUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
