<?php

namespace common\components;

use cms\models\EventGroup;
use cms\models\LogEventUser;
use Yii;
use shakura\yii2\gearman\JobBase;

class Gamification extends JobBase
{

    public function execute(\GearmanJob $job = null)
    {
        if ($job) {
            static::logGamification($this->getWorkload($job)->getParams());
        }
    }

    /**
     * @param $id
     * @param array $params
     * @return bool
     * $params = ['content_id' => $content_id, 'content_name' => $content_name, 'source' => $souce]
     * $id id của sự kiện
     */
    public static function LogGamification($params)
    {
        if (!isset($params['id']))
            return false;
        // Nếu không tồn tại user return false;
        if (!isset($params['user_id']))
            return false;
        else
            $userIdFormat = CFunction::makePhoneNumberStandard($params['user_id']);

        // Lấy toàn bộ event trong db đang ở trạng thái active
        $dataEvents = Yii::$app->db->createCommand('SELECT id, name, event_group_id, point FROM event WHERE status = 1')->queryAll();

        $events = false;
        if (!empty($dataEvents)) {
            foreach ($dataEvents as $key => $value) {
                $events[$value['id']] = (object) array(
                    'name' => $value['name'],
                    'event_group_id' => (int) $value['event_group_id'],
                    'point' => (int) $value['point']
                );
            }
            if (!$events && !isset($events[$params['id']]))
                return false;
            $object = $events[$params['id']];
            $createdTime = strtotime(date('Y-m-d H:i:s', time()));
            $updatedTime = strtotime(date('Y-m-d H:i:s', time()));

            $point = $object->point;
            // Thực hiện cộng điểm cho user
            if (isset($params['user_id']))
                Yii::$app->db->createCommand("UPDATE user SET point = point + $point WHERE id = '$userIdFormat'")->execute();

            // Mảng sự kiện khách hàng cần log
            $paramsLog = [
                'user_id' => $params['user_id'],
                'event_id' => $params['id'],
                'event_name' => $object->name,
                'event_group_id' => $object->event_group_id,
                'event_group_name' => EventGroup::getEventGroupNameById($object->event_group_id),
                'content_id' => isset($params['content_id']) ? (int) $params['content_id'] : '',
                'content_name' => isset($params['content_name']) ? $params['content_name'] : '',
                'point' => $object->point,
                'created_time' => new \MongoDate($createdTime),
                'updated_time' => new \MongoDate($updatedTime),
                'source' => isset($params['source']) ? $params['source'] : '',
                'content_type' =>Yii::$app->params['NOTIFY']['user_point'],
                'read' =>0
            ];

            // Log dữ liệu vào collection log_event_user
            LogEventUser::InsertLog($paramsLog);
        } else {
            return false;
        }
    }
}