<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "students".
 *
 * @property string|null $gender
 * @property string|null $race_ethnicity
 * @property string|null $parental_level_of_education
 * @property string|null $lunch
 * @property string|null $test_preparation_course
 * @property int|null $math_score
 * @property int|null $reading_score
 * @property int|null $writing_score
 * @property int $id
 */
class Students extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['math_score', 'reading_score', 'writing_score'], 'default', 'value' => null],
            [['math_score', 'reading_score', 'writing_score'], 'integer'],
            [['gender'], 'string', 'max' => 10],
            [['race_ethnicity', 'lunch', 'test_preparation_course'], 'string', 'max' => 20],
            [['parental_level_of_education'], 'string', 'max' => 30],
            [['id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gender' => 'Gender',
            'race_ethnicity' => 'Race Ethnicity',
            'parental_level_of_education' => 'Parental Level Of Education',
            'lunch' => 'Lunch',
            'test_preparation_course' => 'Test Preparation Course',
            'math_score' => 'Math Score',
            'reading_score' => 'Reading Score',
            'writing_score' => 'Writing Score',
            'id' => 'ID',
        ];
    }
}
