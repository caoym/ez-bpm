# ez-bpm
这将是一个简单、轻量、健壮、可扩展、适用于自动化为主的、支持主要的BPMN要素（活动、网关、事件）、可持久化、但不准备支持所有BPMN2特性, 的工作流引擎...

## 将可以通过如下方式定义流程

```PHP
$engine = new ProcessEngine();
$process = new Process();
$builder = new ProcessBuilder($process);

//定义流程
$builder
    ->begin
    ->activity(null, CreateOrderTask::class, '创建订单')
    ->eG('eventGetway1', '事件网关')
    ->receiver(null, 'message.pay.{orderId}'， '等待支付')
    ->activity(null, ShipTask::cass, '发货');
    ->xG('xGetway1', '排他网关')
    ->end;
    
$builder 
    ->eventGetway1
    ->timer(null, 3600, '支付超时')
    ->xGetway1
    ->end
    
//执行流程
$process->run($engine);

```
