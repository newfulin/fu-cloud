<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => '字段 :attribute 必须要是可被接受的 .',
    'active_url' => '字段 :attribute 不是一个合法的URL.',
    'after' => '字段 :attribute 必须是后于 :date 的一个日期.',
    'after_or_equal' => '字段 :attribute 必须是一个后于或者跟 :date 相同的一个日期.',
    'alpha' => '字段 :attribute 只能包含字母.',
    'alpha_dash' => '字段 :attribute 只能包含字母 数字 和破折号 .',
    'alpha_num' => '字段 :attribute 只能包含字母和数字 .',
    'array' => '字段 :attribute 必须是一个数组.',
    'before' => '字段 :attribute 必须是先于 :date 的一个日期.',
    'before_or_equal' => '字段 :attribute 必须是一个先于或者跟 :date 相同的日期.',
    'between' => [
        'numeric' => '字段 :attribute 必须介于 :min 和 :max 大小之间.',
        'file' => '字段 :attribute 必须介于 :min 和 :max 千字节.',
        'string' => '字段 :attribute 必须介于 :min 和 :max 个字符.',
        'array' => '字段 :attribute 必须包含介于 :min 和 :max 个子元素.',
    ],
    'boolean' => '字段 :attribute 必须为 true 或者 false.',
    'confirmed' => '字段 :attribute 再次确认跟之前不匹配.',
    'date' => '字段 :attribute 不是一个合法的日期格式.',
    'date_format' => '字段 :attribute 日期格式跟 :format 不匹配.',
    'different' => '字段 :attribute 和 :other 必须不同.',
    'digits' => '字段 :attribute 必须是 :digits 大小的数值.',
    'digits_between' => '字段 :attribute 必须介于 :min 和 :max 数值之间.',
    'dimensions' => '字段 :attribute 的图像尺寸无效.',
    'distinct' => '字段 :attribute 字段值具有重复的值.',
    'email' => '字段 :attribute 必须是一个合法的地址.',
    'exists' => '选中的字段 :attribute 不合法.',
    'file' => '字段 :attribute 必须是文件.',
    'filled' => '字段 :attribute 字段必须有值.',
    'image' => '字段 :attribute 必须是图片文件.',
    'in' => '选中的字段 :attribute 不合法.',
    'in_array' => '字段 :attribute 字段不存在于 :other.',
    'integer' => '字段 :attribute 必须是整型.',
    'ip' => '字段 :attribute 必须是一个合法的IP地址.',
    'ipv4' => '字段 :attribute 必须是一个合法的 IPv4 地址.',
    'ipv6' => '字段 :attribute 必须是一个合法的 IPv6 地址.',
    'json' => '字段 :attribute 必须是一个合法的 JSON 字符串.',
    'max' => [
        'numeric' => '字段 :attribute 不应该比 :max 大.',
        'file' => '字段 :attribute 不应该比 :max 千字节 长.',
        'string' => '字段 :attribute 不应该比 :max 个字符 长.',
        'array' => '字段 :attribute 不能超过 :max 个子元素.',
    ],
    'mimes' => '字段 :attribute 必须是 type: :values 格式的文件.',
    'mimetypes' => '字段 :attribute 必须是 type: :values 格式的文件.',
    'min' => [
        'numeric' => '字段 :attribute 必须最小是 :min.',
        'file' => '字段 :attribute 必须最短是 :min 千字节.',
        'string' => '字段 :attribute 必须最短是 :min 个字符.',
        'array' => '字段 :attribute 必须最少有 :min 个子元素.',
    ],
    'not_in' => '选中的字段 :attribute 不合法.',
    'numeric' => '字段 :attribute 必须是一个数字.',
    'present' => '字段 :attribute 字段必须有.',
    'regex' => '字段 :attribute 格式无效.',
    'required' => '字段 :attribute 是必须的.',
    'required_if' => ' 当 :other的值是 :value,字段 :attribute 是必须的.',
    'required_unless' => '字段 :attribute 是必须的,除非 :other 的值是 :values.',
    'required_with' => '当 :values 存在时,字段 :attribute 必须的.',
    'required_with_all' => ' 当 :values 存在时,字段 :attribute 是必须的.',
    'required_without' => ' 当 :values 不存在时,字段 :attribute 是必须的.',
    'required_without_all' => '当没有:values 存在时,字段 :attribute 是必须的 .',
    'same' => '字段 :attribute 和 :other 必须匹配.',
    'size' => [
        'numeric' => '字段 :attribute 必须是 :size 大小.',
        'file' => '字段 :attribute 必须是 :size 千字节.',
        'string' => '字段 :attribute 必须是 :size 个字符.',
        'array' => '字段 :attribute 必须包含 :size 个子元素.',
    ],
    'string' => '字段 :attribute 必须是一个字符串.',
    'timezone' => '字段 :attribute 必须是有效的时区.',
    'unique' => '字段 :attribute 已经用过了.',
    'uploaded' => '字段 :attribute 上传失败.',
    'url' => '字段 :attribute 格式无效.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
    'identitycards' => '必须是正确的身份证号.',
    'mobile' => '必须是正确的手机号.',
    'accountno' => '必须是正确的银行卡号.',

];
