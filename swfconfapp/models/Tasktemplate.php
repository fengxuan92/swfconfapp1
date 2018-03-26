<?php

namespace app\models;
use yii;


/**
 * This is the model class for table "task_template".
 *
 * @property integer $id
 * @property string $name
 * @property string $template_type
 * @property string $Validation_type
 * @property string $init_param
 * @property string $trigger_url
 * @property string $check_url
 * @property string $result_code
 * @property string $conseq_template
 * @property string $prereq_tasks
 * @property string $manual_close
 * @property string $manual_reason
 * @property string $due_time
 * @property string $user_name
 * @property string $mandatory
 * @property integer $project_id
 * @property integer $frombranch_id
 * @property integer $tobranch_id
 */
class Tasktemplate extends \yii\db\ActiveRecord
{
	private $_templateArr;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'task_template';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
				[['name', 'template_type', 'Validation_type', 'manual_close', 'mandatory'], 'required'],
				[['id', 'project_id', 'frombranch_id', 'tobranch_id'], 'integer'],
				['due_time','validateDue_time'],
				[['name', 'conseq_template', 'prereq_tasks'], 'string','max' => 255],
				[[ 'trigger_url', 'check_url'], 'string','max' => 4000],
				['conseq_template_array','validate_conseq_template_array', 'params'=>['max'=>255]],
				['prereq_tasks_array','validate_prereq_tasks_array', 'params'=>['max'=>255]],
				[['template_type', 'Validation_type'], 'string', 'max' => 20],
				['init_param', 'validateInit_param'],
				[['result_code', 'manual_close', 'mandatory'], 'string', 'max' => 1],
				[['manual_reason'], 'string', 'max' => 400],
				[['user_name'], 'string', 'max' => 50],
				[['id'], 'unique'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
				'id' => 'ID',
				'name' => 'Name',
				'template_type' => 'Template Type',
				'Validation_type' => 'Validation Type',
				'init_param' => 'Init Param',
				'validateInit_param' => 'Init Param',
				'trigger_url' => 'Trigger Url',
				'check_url' => 'Check Url',
				'result_code' => 'Result Code',
				'conseq_template' => 'Conseq Template',
				'conseq_template_array'=> 'Conseq Template',
				'prereq_tasks_array'=> 'Prereq Tasks',
				'prereq_tasks' => 'Prereq Tasks',
				'manual_close' => 'Manual Close',
				'manual_reason' => 'Manual Reason',
				'due_time' => 'Due Time',
				'user_name' => 'User Name',
				'mandatory' => 'Mandatory',
				'project_id' => 'Project ID',
				'frombranch_id' => 'Frombranch ID',
				'tobranch_id' => 'Tobranch ID',
		];
	}
	public function validate_prereq_tasks_array($attribute, $params, $validator)
	{
		$len=0;
		if(!isset($params['max'])) return;
		foreach ($this->prereq_tasks_array as $user){
			$len += 1+sizeof($user);
		}
		if($len > $params['max']){
			$this->addError($attribute,"The selected users must not exceed {$params['max']} characters.");
		}
	}

	/**
	 * helper for array access to allow_user
	 * @return array
	 */
	public function getPrereq_tasks_array()
	{
		if(!isset($this->_templateArr)){
			$this->_templateArr= ($this->conseq_template===";") ? array() : explode(";",$this->conseq_template);
		}
		return $this->_templateArr;
	}

	/**
	 * helper for array access to allow_user
	 * @return array
	 */
	public function setPrereq_tasks_array($array)
	{
		if(!is_array($array)) $array=null;
		$this->_templateArr=$array;
		if($array){
			$this->prereq_tasks= implode(";", $array);
		}else {
			$this->prereq_tasks= null;
		}
	}


	public function validate_conseq_template_array($attribute, $params, $validator)
	{
		$len=0;
		if(!isset($params['max'])) return;
		foreach ($this->conseq_template_array as $user){
			$len += 1+sizeof($user);
		}
		if($len > $params['max']){
			$this->addError($attribute,"The selected users must not exceed {$params['max']} characters.");
		}
	}

	/**
	 * helper for array access to allow_user
	 * @return array
	 */
	public function getConseq_template_array()
	{
		if(!isset($this->_templateArr)){
			$this->_templateArr= ($this->conseq_template===";") ? array() : explode(";",$this->conseq_template);
		}
		return $this->_templateArr;
	}

	/**
	 * helper for array access to allow_user
	 * @return array
	 */
	public function setConseq_template_array($array)
	{
		if(!is_array($array)) $array=null;
		$this->_templateArr=$array;
		if($array){
			$this->conseq_template= implode(";", $array);
		}else {
			$this->conseq_template= null;
		}
	}

	public function validateInit_param($attribute,$params,$validator)
	{   
		$str=$this->$attribute;
		if(strpos($str,"commits")){
			$str=str_ireplace("\${calixwf.context.commits}","[]", $str);				
		}
		$ob=json_decode($str);		
		if ($ob===null) {
			$this->addError($attribute, 'The Init_param must be json format.');			
		}
	} 
	
	public function validateDue_time($attribute,$params,$validator){
		$d=date_create_from_format("U",$this->$attribute);
		$this->$attribute=date_format($d, "Y/m/d H:i:s");
	}
    public function getDue_time($attribute){
    	
    	return $this->$attribute;
    }
}
