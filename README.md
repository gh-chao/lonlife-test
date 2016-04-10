# lonlife-test


## 抓取备案信息

命令帮助
```shell
lonlife-test git:(master) bin/console icp:license:crawler -h
Usage:
  icp:license:crawler <province> <start> <end>

Arguments:
  province              省份
  start                 起始地址
  end                   结束地址

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
 命令格式：
     bin/console icp:license:crawler 省份代号 起始号码 结束号码
 例如：
     bin/console icp:license:crawler 京 030173 030273
```

运行

```php
bin/console icp:license:crawler 京 030173 030273
```

结果：

```php
Array
(
    [company_name] => 北京易方达咨询服务有限公司
    [company_nature] => 企业
    [license_number] => 京ICP证030245号-1
    [website_name] => 保健品招商网
    [website_index] => www.bjpzs.com
    [checked_date] => 2014-03-27
)
Array
(
    [company_name] => 中国工商银行股份有限公司
    [company_nature] => 企业
    [license_number] => 京ICP证030247号-5
    [website_name] => 中国工商银行主站
    [website_index] => www.95588.com
    [checked_date] => 2012-11-02
)
Array
(
    [company_name] => 北京中人网信息咨询有限公司
    [company_nature] => 企业
    [license_number] => 京ICP证030255号-1
    [website_name] => 中人网
    [website_index] => www.chinahrd.net
    [checked_date] => 2013-03-07
)
Array
(
    [company_name] => 国开证券有限责任公司
    [company_nature] => 企业
    [license_number] => 京ICP证030258号-1
    [website_name] => 国开证券
    [website_index] => www.gkzq.com.cn
    [checked_date] => 2015-08-11
)
Array
(
    [company_name] => 北京互众数码科技有限公司
    [company_nature] => 个人
    [license_number] => 京ICP证030259号-1
    [website_name] => 互众数码
    [website_index] => www.interman.cn
    [checked_date] => 2005-06-27
)

```