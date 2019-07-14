### 启动方式

php easyswoole start produce

端口： 8008

## 获取用户余额

#### 签名格式

1. 将参数按照 ASCII 码的顺序排序。
2. 然后使用&将请求的参数串联起来。
3. 使用 AES 算法签名。



#### 请求格式：

 对于 GET 请求，所有的参数都是在路径参数中，其他的参数如果没有特殊说明都是放在请求体中的。

##### 基本请求参数

每个请求都必带的参数

| 参数名称  | 数据类型 | 是否必须 | 默认值 | 说明                       | 例子                |
| --------- | -------- | -------- | ------ | -------------------------- | ------------------- |
| exchange  | string   | true     | N/A    | 交易所名称                 | huobi               |
| apiKey    | string   | true     | N/A    | api key                    | xxxx-xxxx-xxxx-xxxx |
| secretKey | string   | true     | N/A    | secret key                 | xxxx-xxxx-xxxx-xxxx |
| timestamp | integer  | true     | N/A    | 当前时间戳，单位秒         | 1562854031202       |
| nonce     | integer  | true     | N/A    | 随机数                     | 66666               |
| version   | string   | false    | N/A    | api 版本号，有的可能需要传 | 3                   |
| password  | string   | false    | N/A    | 密码，okex 可能要传        | xxxxxxxx            |



#### 返回格式：

 所有的接口返回的格式都是 JSON 格式。格式如下：

```json
{
  "status": "Error",
  "ts": 1562854031202,
  "msg": "xxxxxxxxxx",
  "code": "Success",
  "data": null
}
```

| 参数名称 | 数据类型 | 描述                                    |
| -------- | -------- | --------------------------------------- |
| status   | string   | 接口返回类型，example: Error \| Success |
| ts       | integer  | 当前时间戳                              |
| msg      | string   | 响应消息                                |
| code     | string   | 响应代码                                |
| data     | object   | 数据主体                                |



#### 获取余额

 返回用户的余额。

###### HTTP 请求

- `GET` `/v1/q/balance`

###### 请求参数

 该接口不接受其他参数。

###### example:

```bash
curl "$hostName:$port/v1/q/balance?exchange=huobipro&apiKey=$apiKey&secretKey=$secretKey&timestamp=xxxx&nonce=$RANDOM"
```

```json
{
  "status": "Success",
  "ts": 1562997288351,
  "msg": "测试",
  "code": "Success",
  "data": {}
}
```



#### 获取订单

 获取某个 Symbol 的订单。

######　 HTTP 请求

- `GET` `/v1/q/orders`

###### 请求参数

| 参数名称 | 参数类型 | 是否必须 | 默认值 | 说明        | 例子     |
| -------- | -------- | -------- | ------ | ----------- | -------- |
| symbol   | string   | true     | N/A    | symbol 名称 | BTC/USDT |

###### example:

```bash
curl "$hostName:$port/v1/q/order?exchange=huobipro&apiKey=$apiKey&secretKey=$secretKey&timestamp=xxxx&nonce=$RANDOM&symbol=BTC/USDT"
```

```json

```

 获取 id 的

###### HTTP 请求

- `GET` `/v1/q/order`

###### 请求参数

| 参数名称 | 参数类型 | 是否必须 | 默认值 | 说明                              | 例子      |
| -------- | -------- | -------- | ------ | --------------------------------- | --------- |
| symbol   | string   | true     | N/A    | 交易对                            | BTC/USDT  |
| orderId  | string   | true     | N/A    | 订单的 id，通过这个 id 去获取订单 | 440464866 |



## 下单

 创建订单。

###### HTTP 请求：

- `GET` `/v1/order/create`

###### 请求参数

| 参数名称 | 参数类型 | 是否必须 | 默认值 | 说明                              | 例子     |
| -------- | -------- | -------- | ------ | --------------------------------- | -------- |
| symbol   | string   | true     | N/A    | 交易对                            | BTC/USDT |
| side     | string   | true     | buy    | 买单/卖单,买单为buy，sell为卖单。 | buy      |


