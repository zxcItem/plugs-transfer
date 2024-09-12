<?php

declare (strict_types=1);

namespace plugin\transfer\model;

use plugin\account\model\Abs;
use plugin\account\model\PluginAccountUser;
use think\model\relation\HasOne;

class PluginUserTransfer extends Abs
{
    /**
     * 关联当前用户
     * @return \think\model\relation\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(PluginAccountUser::class, 'id', 'unid');
    }

    /**
     * 绑定用户数据
     * @return HasOne
     */
    public function bindUser(): HasOne
    {
        return $this->user()->bind([
            'user_phone'       => 'phone',
            'user_headimg'     => 'headimg',
            'user_username'    => 'username',
            'user_nickname'    => 'nickname',
            'user_create_time' => 'create_time',
        ]);
    }

    /**
     * 格式化输出时间
     * @param mixed $value
     * @return string
     */
    public function getChangeTimeAttr($value): string
    {
        return format_datetime($value);
    }

    /**
     * 自动显示类型名称
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        if (isset($data['type'])) {
            $map = ['platform' => '平台发放'];
            $data['type_name'] = $map[$data['type']] ?? (UserTransfer::types[$data['type']] ?? $data['type']);
        }
        return $data;
    }
}