<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014
 * @package yii2-widgets
 * @subpackage yii2-widget-sidenav
 * @version 1.0.0
 */

namespace cms\widgets;

use common\components\CFunction;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * A custom extended side navigation menu extending Yii Menu
 *
 * For example:
 *
 * ```php
 * echo SideNav::widget([
 *     'items' => [
 *         [
 *             'url' => ['/site/index'],
 *             'label' => 'Home',
 *             'icon' => 'home'
 *         ],
 *         [
 *             'url' => ['/site/about'],
 *             'label' => 'About',
 *             'icon' => 'info-sign',
 *             'items' => [
 *                  ['url' => '#', 'label' => 'Item 1'],
 *                  ['url' => '#', 'label' => 'Item 2'],
 *             ],
 *         ],
 *     ],
 * ]);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
class SideNav extends Menu
{

    /**
     * @var string prefix for the icon in [[items]]. This string will be prepended
     * before the icon name to get the icon CSS class. This defaults to `glyphicon glyphicon-`
     * for usage with glyphicons available with Bootstrap.
     */
    public $iconPrefix = 'fa fa-';

    /**
     * @var array string/boolean the sidenav heading. This is not HTML encoded
     * When set to false or null, no heading container will be displayed.
     */
    public $heading = false;

    /**
     * @var array options for the sidenav heading
     */
    public $headingOptions = [];

    /**
     * @var array options for the sidenav container
     */
    public $containerOptions = [];

    /**
     * @var string indicator for a menu sub-item
     */
    public $indItem = '';

    /**
     * @var string indicator for a opened sub-menu
     */
    public $indMenuOpen = '';

    /**
     * @var string indicator for a closed sub-menu
     */
    public $indMenuClose = '';

    /**
     * @var array list of sidenav menu items. Each menu item should be an array of the following structure:
     *
     * - label: string, optional, specifies the menu item label. When [[encodeLabels]] is true, the label
     *   will be HTML-encoded. If the label is not specified, an empty string will be used.
     * - icon: string, optional, specifies the glyphicon name to be placed before label.
     * - url: string or array, optional, specifies the URL of the menu item. It will be processed by [[Url::to]].
     *   When this is set, the actual menu item content will be generated using [[linkTemplate]];
     * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
     * - items: array, optional, specifies the sub-menu items. Its format is the same as the parent items.
     * - active: boolean, optional, whether this menu item is in active state (currently selected).
     *   If a menu item is active, its CSS class will be appended with [[activeCssClass]].
     *   If this option is not set, the menu item will be set active automatically when the current request
     *   is triggered by [[url]]. For more details, please refer to [[isItemActive()]].
     * - template: string, optional, the template used to render the content of this menu item.
     *   The token `{url}` will be replaced by the URL associated with this menu item,
     *   and the token `{label}` will be replaced by the label of the menu item.
     *   If this option is not set, [[linkTemplate]] will be used instead.
     * - options: array, optional, the HTML attributes for the menu item tag.
     *
     */
    public $items;

    public function init()
    {
        parent::init();
        $this->activateParents = true;
        $this->submenuTemplate = "\n<ul class='nav nav-second-level'>\n{items}\n</ul>\n";
        $this->submenuTemplateLevel = "\n<ul class='nav nav-third-level'>\n{items}\n</ul>\n";
        $this->linkTemplate = '<a href="{url}">{icon}{label}</a>';
        $this->labelTemplate = '{icon}{label}';
        $this->markTopItems();
        Html::addCssClass($this->options, 'nav');
        CFunction::addCssId($this->options, 'side-menu');
    }

    /**
     * Renders the side navigation menu.
     * with the heading and panel containers
     */
    public function run()
    {
        $body = '<div class="sidebar-collapse">'.$this->renderMenu().'</div>';
        Html::addCssClass($this->containerOptions, "navbar-default navbar-static-side");
        echo Html::tag('nav', $body, $this->containerOptions);
    }

    /**
     * Renders the main menu
     */
    protected function renderMenu()
    {

        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = $_GET;
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        $liHeading = '<li class="nav-header">
                        <div class="dropdown profile-element">
                            <span><img alt="image" class="img-circle" src="'.CFunction::getImageBaseUrl().'app/icon-user.png" /></span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                                <span class="clear">
                                    <span class="block m-t-xs">
                                        <strong class="font-bold">'.ucfirst(Yii::$app->user->identity->fullname).'</strong>
                                    </span>
                                    <span class="text-muted text-xs block">'.Yii::$app->user->identity->username.'<b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="'.Url::toRoute(['admin/view', 'id' => Yii::$app->user->getId()]).'">'.Yii::t('cms', 'profile').'</a></li>
                                <li><a href="'.Url::toRoute(['default/logout']).'">'.Yii::t('cms', 'logout').'</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">VG+</div>
                     </li>';
        return Html::tag($tag, $liHeading.$this->renderItems($items), $options);
    }

    /**
     * Marks each topmost level item which is not a submenu
     */
    protected function markTopItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            if (empty($item['items'])) {
                $item['top'] = true;
            }
            $items[] = $item;
        }
        $this->items = $items;
    }

    /**
     * Renders the content of a side navigation menu item.
     *
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    protected function renderItem($item)
    {
        $this->validateItems($item);
        $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
        $url = Url::to(ArrayHelper::getValue($item, 'url', '#'));
        if ($item['label'] == 'Dashboard') {
            $template = '<a href="'.Yii::$app->homeUrl.'">{icon}<span class="nav-label">{label}</span></a>';
        }
        if (empty($item['top'])) {
            if (empty($item['items'])) {
                $template = str_replace('{icon}', $this->indItem . '{icon}', $template);
            } else {
                if ($item['level'] == 2)
                    $template = '<a href="{url}">{icon}{label}</a>';
                else
                    $template = isset($item['template']) ? $item['template'] : '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>';
                $openOptions = ($item['active']) ? ['class' => 'opened fa arrow'] : ['class' => 'opened fa arrow', 'style' => 'display:none'];
                $closeOptions = ($item['active']) ? ['class' => 'closed fa arrow', 'style' => 'display:none'] : ['class' => 'closed fa arrow'];
                $indicator = Html::tag('span', $this->indMenuOpen, $openOptions) . Html::tag('span', $this->indMenuClose, $closeOptions);
                $template = str_replace('{icon}', $indicator . '{icon}', $template);
            }
        }
        $icon = empty($item['icon']) ? '' : '<i class="' . $this->iconPrefix . $item['icon'] . '"></i>';
        unset($item['icon'], $item['top']);
        return strtr($template, [
            '{url}' => $url,
            '{label}' => $item['label'],
            '{icon}' => $icon
        ]);
    }

    /**
     * Validates each item for a valid label and url.
     *
     * @throws InvalidConfigException
     */
    protected function validateItems($item)
    {
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
    }
}