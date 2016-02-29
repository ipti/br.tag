<?php
/**
 *
 * @property string $inep_id
 *
 * The followings are the available model relations:
 * @property Calendar[] $calendars
 * @property Calendar $actualCalendar
 */
class CalendarSchool extends CActiveRecord{
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'school_identification';
    }

    public function relations() {
        return [
            'calendars' => [self::HAS_MANY, 'Calendar', 'school_fk']
        ];
    }

    public function getActualCalendar(){
        /** @var @var $calendar Calendar*/
        foreach($this->calendars as $calendar){
            if($calendar->actual) return $calendar;
        }
        return new Calendar();

    }

}