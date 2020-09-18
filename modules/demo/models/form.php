<?php
/**
 * @filesource modules/demo/models/form.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Form;

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
        if ($request->initSession() && $request->isSafe() && Login::isMember()) {
            try {
                /**
                 * รับค่าจากการ POST ที่ส่งมากับการ submit ฟอร์ม
                 *
                 * @source http://doc.kotchasan.com/class-Kotchasan.InputItem.html
                 */
                $save = array(
                    'username' => $request->post('register_username')->username(),
                    'password' => $request->post('register_password')->password(),
                    'repassword' => $request->post('register_repassword')->password(),
                    'date' => $request->post('register_date')->date(),
                    'time' => $request->post('register_time')->date(),
                    'amount' => $request->post('register_amount')->toDouble(),
                    'color1' => $request->post('register_color1')->filter('0-9A-Z\#'),
                    'phone' => $request->post('register_phone')->number(),
                    'color2' => $request->post('register_color2')->filter('0-9A-Z\#'),
                    'sex' => $request->post('register_sex')->filter('fm'),
                    'sex' => $request->post('register_sex')->filter('fm'),
                    'address' => $request->post('register_address')->textarea(),
                    'url' => $request->post('register_url')->url(),
                    'email' => $request->post('register_email')->url(),
                    'provinceID' => $request->post('register_provinceID')->number(),
                    'zipcode' => $request->post('register_zipcode')->number(),
                    'permission' => $request->post('register_permission', array())->topic(),
                    'social' => $request->post('register_social', array())->toInt(),
                    'id' => $request->post('register_id')->toInt(),
                );
                // ดูค่าที่ส่งมา แสดงผลใน console ของ Browser
                //print_r($_POST);
                //print_r($save);
                if ($save['username'] == '') {
                    /**
                     * error ไม่ได้กรอก username
                     * ret_ เป็นคีย์เวอร์ดเพื่อบอกว่าเป็นการส่งค่ากลับไปยัง input
                     * register_username ไอดีของ input ที่ต้องการส่งค่ากลับ
                     */
                    $ret['ret_register_username'] = 'Please fill in';
                }
                if (empty($ret)) {
                    // บันทึกลงฐานข้อมูล (แก้ไข)
                    //$this->db()->update($this->getTableName('user'), $save['id'], $save);
                    // คืนค่าข้อความแจ้งเตือนสำเร็จ
                    $ret['alert'] = Language::get('Saved successfully');
                    // รีไดเร็คไปหน้าแสดงรายการข้อมูล ด้วยพารามิเตอร์ต่างๆของตารางที่เลือกไว้
                    $ret['location'] = $request->getUri()->postBack('index.php', array('module' => 'demo-table', 'id' => null));
                    // รีไดเร็คกลับไปหน้าตาราง
                    //$ret['location'] = WEB_URL.'index.php?module=demo-table';
                    // รีโหลดฟอร์ม
                    //$ret['location'] = 'reload';
                    // เคลียร์ token
                    $request->removeToken();
                }
            } catch (\Kotchasan\InputItemException $e) {
                $ret['alert'] = $e->getMessage();
            }
        }
        if (empty($ret)) {
            // แจ้งเตือนการ submit ไม่ถูกต้อง
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
