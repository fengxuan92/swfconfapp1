<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "cwd_user".
 *
 * @property int $ID
 * @property string $user_name
 * @property string $active
 * @property string $display_name
 * @property string $email_address
 * @property string $type
 */
class CwdUser extends \yii\db\ActiveRecord implements IdentityInterface
{
	private $_crowdsvr; 
	
	/**
	 * @inheritdoc
	 */
	public function getCrowdSvr()
	{
		if (!is_object($this->_crowdsvr)){
			$this->_crowdsvr = Yii::createObject([
					'class' => AtlassianRestApi::className(),
			]);
		}
		return $this->_crowdsvr;
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cwd_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	[['user_name', 'display_name'], 'required'],
            [['ID'], 'integer'],
            [['user_name'], 'string', 'max' => 50],
        	[['active'], 'string', 'max' => 1],
        	[['display_name', 'email_address', 'type'], 'string', 'max' => 255],
        	[['ID'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'user_name' => Yii::t('app', 'User Name'),
            'active' => Yii::t('app', 'Active'),
            'display_name' => Yii::t('app', 'Display Name'),
            'email_address' => Yii::t('app', 'Email Address'),
            'type' => Yii::t('app', 'Type'),
        ];
    }
    
    public function getUsername(){
    	return $this->display_name;
    }

    /**
     * @inheritdoc
     * @return CwdUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CwdUserQuery(get_called_class());
    }
    
	public static function findIdentity($id) {
		return static::findOne ( $id );
	}
	public static function findIdentityByAccessToken($token, $type = null) {
		return static::findOne ( [ 
				'access_token' => $token 
		] );
	}
	
	
	public function updateCrowdUser($cwdUser)
	{
		$result = static::find()->where(['user_name'=>$cwdUser->name])->one();
		if( !is_object($result) ){
			$result = new CwdUser();
			$result->loadDefaultValues();
		}
		$result->attributes = [
				'user_name' => $cwdUser->name,
				'display_name' => $cwdUser->{"display-name"},
				'active' => $cwdUser->active ? "Y" : "N",
				'email_address' => $cwdUser->email,
			];
		$result->save(false);
		return $result;
	}
	
	public static function syncCrowdUsers()
	{
		$allDbUsers = static::find()->orderBy('ID')->all();
		$crowdsvr = Yii::createObject([
				'class' => AtlassianRestApi::className(),
		]);
		$allCrowdUsers = $crowdsvr->getAllUsers();
		
		asort($allCrowdUsers);
		foreach ($allDbUsers as $oneDbUser){
			if( array_key_exists( $oneDbUser->user_name, $allCrowdUsers ) ){
				// find this user, update it and remove from current check list
				$cwdUser = $allCrowdUsers[$oneDbUser->user_name];
				$oneDbUser->attributes = [
						'user_name' => $cwdUser->name,
						'display_name' => $cwdUser->{"display-name"},
						'active' => $cwdUser->active ? "Y" : "N",
						'email_address' => $cwdUser->email,
						];
				unset($allCrowdUsers[$oneDbUser->user_name]);
			} else {
				// this user is not exist any more, mark it in active
				$oneDbUser->active = 'N';
			}
		}
		foreach ($allCrowdUsers as $name => $cwdUser) {
			$newUser=new CwdUser();
			$newUser->loadDefaultValues();
			$newUser->attributes = [
					'user_name' => $cwdUser->name,
					'display_name' => $cwdUser->{"display-name"},
					'active' => $cwdUser->active ? "Y" : "N",
					'email_address' => $cwdUser->email,
					];
			$allDbUsers[] = $newUser;
		}
		$transaction=CwdUser::getDb()->beginTransaction();
		try{
		  foreach ($allDbUsers as $oneDbUser){
			$oneDbUser->save(false);
		  }
		  $transaction->commit();
		}catch (\Exception $e){
			$transaction->rollback();
			throw $e;
		}catch (\Throwable $e){
			$transaction->rollback();
			throw $e;
		}
	}
	
	public static function findByUsername($name){
		$result = static::find()->where(['user_name'=>$name])->one();
		if( !is_object($result) ){
			// this user is not exist, ask Crowd if exist
			$crowdsvr = Yii::createObject([
					'class' => AtlassianRestApi::className(),
					]);
			$cwdUser = $crowdsvr->findByUsername($name);
			if(is_object($cwdUser)){
				$result = new CwdUser();
				$result->loadDefaultValues();
				$result->attributes = [
						'user_name' => $cwdUser->name,
						'display_name' => $cwdUser->{"display-name"},
						'active' => $cwdUser->active ? "Y" : "N",
						'email_address' => $cwdUser->email,
						];
				$result->save(false);
			}
		}
		return $result;
	}
	
	public function getId() {
		return $this->ID;
	}
	
	public function getAuthKey() {
		return $this->user_name;
	}
	
	public function validateAuthKey($authKey) {
		return $this->user_name === $authKey;
	}
	
	public function validatePassword($passwd){
		$cObj=$this->getCrowdSvr()->authenUser( $this->user_name,$passwd );
		if( is_object($cObj)){
			$this->updateCrowdUser($cObj);
			return true;
		} else {
			return false;
		}
	}
}
