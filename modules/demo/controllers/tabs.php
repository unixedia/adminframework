<?php
/**
 * @filesource modules/demo/controllers/tabs.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Tabs;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;

/**
 * module=demo-tabs
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * @param  $request
     * @param  $id
     * @param  $login
     *
     * @return mixed
     */
    public static function createTab($request, $id, $login)
    {
        $uri = $request->createUriWithGlobals(WEB_URL.'index.php');
        $submenus = array();
        // สามารถจัดการ tab Actions ได้
        if (Login::checkPermission($login, array('can_view_action', 'can_add_action', 'can_delete_action'))) {
            $submenus[] = array(
                'title' => 'ผลการติดตาม',
                'id' => 'actions',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'actions')),
            );
        }
        if (Login::checkPermission($login, 'can_view_paid')) {
            $submenus[] = array(
                'title' => 'รายการชำระ',
                'id' => 'paids',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'paids')),
            );
        }
        $submenus[] = array(
            'title' => 'ข้อมูลคดี',
            'id' => 'lawsuitdetail',
            'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'lawsuitdetail')),
        );
        if (Login::checkPermission($login, 'can_view_other')) {
            $submenus[] = array(
                'title' => 'เงินเดือน',
                'id' => 'salarys',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'salarys')),
            );
            $submenus[] = array(
                'title' => 'บัญชีเงินฝาก',
                'id' => 'deposits',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'deposits')),
            );
            $submenus[] = array(
                'title' => 'ห้องชุด',
                'id' => 'condos',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'condos')),
            );
            $submenus[] = array(
                'title' => 'ที่ดิน',
                'id' => 'lands',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'lands')),
            );
            $submenus[] = array(
                'title' => 'เช่าซื้อ',
                'id' => 'rents',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'rents')),
            );
            $submenus[] = array(
                'title' => 'ขายทอดตลาด',
                'id' => 'auctions',
                'href' => $uri->createBackUri(array('module' => 'contract', 'id' => $id, 'tab' => 'auctions')),
            );
        }
        $tab = new \Kotchasan\Tab('accordient_menu', $uri->createBackUri(array('module' => 'contract', 'id' => $id)), $submenus);

        return $tab;
    }

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
        $this->title = 'Tabs';
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
            $header = $section->add('header', array(
                'innerHTML' => '<h2 class="icon-menus">'.$this->title.'</h2>',
            ));
            // เมนู tab
            $tab = new \Kotchasan\Tab('accordient_menu', 'index.php?module=demo-tabs');
            $tab->add('upload', '{LNG_Ajax Upload}');
            $tab->add('lms', '{LNG_Listbox multi select}');
            $tab->add('table', '{LNG_Table}');
            $header->appendChild($tab->render($request->request('tab')->filter('a-z')));
            // tab ที่เลือก
            switch ($tab->getSelect()) {
                case 'upload':
                    $className = 'Demo\Upload\View';
                    break;
                case 'table':
                    $className = 'Demo\Table\View';
                    break;
                default:
                    $className = 'Demo\Multiselect\View';
                    break;
            }
            // โหลดฟอร์ม (View)
            $section->appendChild(createClass($className)->render($request));
            // คืนค่า HTML

            return $section->render();
        }
        // 404

        return \Index\Error\Controller::execute($this);
    }
}
