<?php
/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $user_role__id
 *
 * The followings are the available model relations:
 * @property UserRole $userRole
 */
class Users extends CActiveRecord {
   
	 private $_identity;

    public $password_repeat;
	
   public function tableName() {
        return 'users';
    }

    public function rules() {
        return array(
            array('username, password', 'required'),
            array('user_role__id', 'numerical', 'integerOnly'=>true),
            array('username, email', 'length', 'max'=>128),
            array('password', 'length', 'max'=>64),
            array('id, username, password, email, user_role__id', 'safe', 'on'=>'search'),
        );
    }

    public function relations() {
        return array(
            'userRole' => array(self::BELONGS_TO, 'UserRole', 'user_role__id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => Yii::t("main", "Логин"),
            'password' => Yii::t("main", "Пароль"),
            'email' => Yii::t("main", "Email"),
            'user_role__id' => Yii::t("main", "Role")
        );
    }

    public function search() {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('user_role__id',$this->role,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
        
    public function validatePassword($password) {          
        return CPasswordHelper::verifyPassword($password,$this->password);
    }

    public function hashPassword($password) {
        return CPasswordHelper::hashPassword($password);
    }


    protected function beforeSave() {
        $this->password = hashPassword($this->password);
        return parent::beforeSave();
    }
	
	 public function authenticate() {
        if(!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            if(!$this->_identity->authenticate()) {
                $this->addError('password', 'Incorrect username or password');
            }
        }
    }

    public function login() {
        if($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }

        if($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = 3600*24*30;

            Yii::app()->user->login($this->_identity, $duration);
			
            return true;
        }
        else {
            return false;
        }
    }
}
