<?php
/**
 * Referat class file
 * @author cheva
 */

/**
 * This is the model class for table "referat".
 *
 * The followings are the available columns in table 'referat':
 * @property integer $id
 * @property string $theme
 * @property string $theme_alias
 * @property string $title
 * @property string $body
 */
class Referat extends CActiveRecord
{

    /**
     * @var integer ID
     * @soap
     */
    public $id;

    /**
     * @var string theme
     * @soap
     */
    public $theme;

    /**
     * @var string theme alias
     * @soap
     */
    public $theme_alias;

    /**
     * @var string title
     * @soap
     */
    public $title;

    /**
     * @var string body
     * @soap
     */
    public $body;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'referat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('theme, theme_alias, title', 'length', 'max' => 255),
            array('theme, theme_alias, title', 'required'),
            array('body', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, theme, theme_alias, title, body', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'theme' => 'Theme',
            'theme_alias' => 'Theme Alias',
            'title' => 'Title',
            'body' => 'Body',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('theme', $this->theme, true);
        $criteria->compare('theme_alias',$this->theme_alias,true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('body', $this->body, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * scopes to use as AR methods
     * 
     * @return array scopes
     */
    public function scopes() {
        return array(
            'lastRecord' => array(
                'order' => '`id` DESC',
                'limit' => 1,
            ),
        );
    }

}
