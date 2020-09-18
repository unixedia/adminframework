<?php
/**
 * @filesource modules/demo/controllers/autocomplete.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Autocomplete;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;

/**
 * module=demo-autocomplete.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * Controller สำหรับคัดเลือกหน้าของโมดูล demo.
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // ข้อความ title bar
        $this->title = 'Auto Complete';
        // เลือกเมนู
        $this->menu = 'demo';
        // ตรวจสอบ premission (can_config)
        if ($login = Login::checkPermission(Login::isMember(), 'can_config')) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-home">{LNG_Home}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-map">'.$this->title.'</h2>',
            ));
            // แสดงตาราง
            $section->appendChild(createClass('Demo\Autocomplete\View')->render($request));

            return $section->render();
        }
        // 404

        return \Index\Error\Controller::execute($this);
    }
}
