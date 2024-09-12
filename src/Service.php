<?php

declare (strict_types=1);

namespace plugin\transfer;

use plugin\transfer\command\Trans;
use think\admin\Plugin;

/**
 * 组件注册服务
 * @class Service
 * @package app\sign
 */
class Service extends Plugin
{
    /**
     * 定义插件名称
     * @var string
     */
    protected $appName = '提现管理';

    /**
     * 定义安装包名
     * @var string
     */
    protected $package = 'xiaochao/plugs-transfer';

    /**
     * 插件服务注册
     * @return void
     */
    public function register(): void
    {
        $this->commands([Trans::class]);
    }


    /**
     * 签到模块菜单配置
     * @return array[]
     */
    public static function menu(): array
    {
        // 设置插件菜单
        $code = app(static::class)->appCode;
        // 设置插件菜单
        return [
            [
                'name' => '提现管理',
                'subs' => [
                    ['name' => '提现记录管理', 'icon' => 'layui-icon layui-icon-table', 'node' => "{$code}/transfer/index"],
                ],
            ]
        ];
    }
}