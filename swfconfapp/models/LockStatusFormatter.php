<?php

namespace app\models;


/**
 */
class LockStatusFormatter extends \yii\i18n\Formatter
{
	/**
	 * Formats the value as an image tag.
	 * @param mixed $value the value to be formatted.
	 * @param array $options the tag options in terms of name-value pairs. See [[Html::img()]].
	 * @return string the formatted result.
	 */
	public function asLockStatus($value, $options = [])
	{
		if ($value === null) {
			return $this->nullDisplay;
		}
		switch ($value){
			case 'M':
				return $this->asImage('images/yellow.png',['width'=>'40','height'=>'40','value'=>'h']);
				//return Html::tag('div','half Open', [ 'class'=>'rndbutton statusPartOpen btn-xs' ]);
			case 'O':
				return $this->asImage('images/green_circle.png',['width'=>'40','height'=>'40','value'=>'h']);
				//return Html::tag('div','Open', [ 'class'=>'rndbutton statusOpen btn-xs' ]);
			case 'L':
				return $this->asImage('images/lock.png',['width'=>'40','height'=>'40','value'=>'h']);
				//return Html::tag('div','Locked', [ 'class'=>'rndbutton statusLocked btn-xs' ]);
			case 'S':
				return $this->asImage('images/lgreen.png',['width'=>'40','height'=>'40','value'=>'h']);
				//return Html::tag('div','Open', [ 'class'=>'rndbutton statusOpen btn-xs' ]);
		}
		//return Html::img($value, $options);
		return "UNKNOWN";
	}

	/**
	 * Formats the value as an image tag.
	 * @param mixed $value the value to be formatted.
	 * @param array $options the tag options in terms of name-value pairs. See [[Html::img()]].
	 * @return string the formatted result.
	 */
	public function asAllowedUsers($value, $options = [])
	{
	  if ($value === null|| empty($value)) return "--ALL USERS--";
	  if ($value === ';') return "--NONE--";
	  if (strlen($value)>12) return substr($value, 0, 12) . "...";
	  return $value;
	}
}
