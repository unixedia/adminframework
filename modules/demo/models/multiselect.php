<?php
/**
 * @filesource modules/demo/models/multiselect.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Multiselect;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * รับค่าจากฟอร์ม
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * รับค่าจากฟอร์ม (form.php).
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, member
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            try {
                // รับค่าจากการ POST
                $save = array(
                    'provinceID' => $request->post('provinceID')->toInt(),
                    'amphurID' => $request->post('amphurID')->toInt(),
                    'districtID' => $request->post('districtID')->toInt(),
                );
                // ดูค่าที่ส่งมา
                //print_r($_POST);
                //print_r($save);
                $result = \Demo\Province\Model::find($save['provinceID'], $save['amphurID'], $save['districtID']);
                $ret['alert'] = var_export($result, true);
                // บันทึกลงฐานข้อมูล
                //$this->db()->update($this->getTableName('user'), $save['id'], $save);
                // คืนค่า
                //$ret['alert'] = Language::get('Saved successfully');
                // ไปหน้าแสดงรายการข้อมูล พร้อมกับส่งค่าที่ส่งมากลับไปแสดงผล
                $ret['location'] = $request->getUri()->postBack('index.php', array('module' => 'demo-multiselect') + $save);
                // เคลียร์
                $request->removeToken();
            } catch (\Kotchasan\InputItemException $e) {
                $ret['alert'] = $e->getMessage();
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
