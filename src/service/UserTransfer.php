<?php

declare (strict_types=1);

namespace plugin\transfer\service;

use plugin\transfer\model\PluginUserTransfer;

/**
 * 代理提现数据服务
 * @class UserTransfer
 * @package plugin\transfer\service
 */
abstract class UserTransfer
{
    // 提现方式配置
    public const types = [
        'wechat_wallet'  => '提现到微信零钱',
        'alipay_account' => '提现到支付宝账户',
        // 'wechat_banks'   => '转账到银行卡账户（线上）', 微信官方已不支持
        // 'wechat_qrcode'  => '提现到微信收款码（线下）',
        // 'transfer_banks' => '提现到银行卡账户（线下）',
        // 'alipay_qrcode'  => '提现到支付宝收款码（线下）',
    ];

    /**
     * 同步刷新代理返佣
     * @param integer $unid
     * @return array [total, count, audit, locks]
     */
    public static function amount(int $unid): array
    {
        if ($unid > 0) {
            $locks = abs(PluginUserTransfer::mk()->whereRaw("unid='{$unid}' and status=3")->sum('amount'));
            $total = abs(PluginUserTransfer::mk()->whereRaw("unid='{$unid}' and status>=1")->sum('amount'));
            $count = abs(PluginUserTransfer::mk()->whereRaw("unid='{$unid}' and status>=4")->sum('amount'));
            $audit = abs(PluginUserTransfer::mk()->whereRaw("unid='{$unid}' and status>=1 and status<3")->sum('amount'));
        } else {
            $locks = abs(PluginUserTransfer::mk()->whereRaw("status=3")->sum('amount'));
            $total = abs(PluginUserTransfer::mk()->whereRaw("status>=1")->sum('amount'));
            $count = abs(PluginUserTransfer::mk()->whereRaw("status>=4")->sum('amount'));
            $audit = abs(PluginUserTransfer::mk()->whereRaw("status>=1 and status<3")->sum('amount'));
        }
        return [$total, $count, $audit, $locks];
    }

    /**
     * 获取提现配置
     * @param ?string $name
     * @return array|string
     * @throws \think\admin\Exception
     */
    public static function config(?string $name = null)
    {
        $ckey = 'plugin.transfer.config';
        $data = sysvar($ckey) ?: sysvar($ckey, sysdata($ckey));
        return is_null($name) ? $data : ($data[$name] ?? '');
    }

    /**
     * 获取转账配置
     * @param ?string $name
     * @return array|string
     * @throws \think\admin\Exception
     */
    public static function payment(?string $name = null)
    {
        $ckey = 'plugin.transfer.wxpay';
        $data = sysvar($ckey) ?: sysvar($ckey, sysdata($ckey));
        return is_null($name) ? $data : ($data[$name] ?? '');
    }
}