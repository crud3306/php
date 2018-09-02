<?php


// 基础示例：
// ==================
function printer() {
    $i = 1;
    while(true) {
    	echo "i am inner \n";
        echo 'this is the yield ' . (yield $i) . "\n";
        $i++;
    }
}

$printer = printer();
echo "start \n";
var_dump($printer->current());
// var_dump($printer->next()); // next不能传递数据，并且无返回值。只是让生成器继续执行到下一个yield。
var_dump($printer->send('first'));
var_dump($printer->send('second'));

// 执行结果：
// ==================
// start
// i am inner
// int(1)
// this is the yield first
// i am inner
// int(2)
// this is the yield second
// i am inner
// int(3)

// 执行过程
// ==================
// 生成器赋值，但内部逻辑并未执行
// 输出start
// 调用var_dump($printer->current()) 时，生成器开始执行，输出 echo "i am inner \n" ，因下一句带yield，暂停返回yield后$i的值。var_dump()打印出$i
// 调用var_dump($printer->send('first')) 时，生成器开始执行，接着上前yield处开始执行，先收到传入的值first作为yield $id在生成器中代表的值，拼入echo 'this is the yield ' . (yield $i) . "\n"中输出，然后$i++，继续while的下一次循环，输出 echo "i am inner \n" ，因下一句带yield，暂停返回yield后$i的值。var_dump()打印出$i
// 


// 更多示例:
// =================
// http://www.laruence.com/2015/05/28/3038.html
// https://www.cnblogs.com/tingyugetc/p/6347286.html






// 迭代器
// ==================
// Iterator extends Traversable {

//     // 返回当前的元素
//     abstract public mixed current(void)

//     // 返回当前元素的键
//     abstract public scalar key(void)

//     // 向下移动到下一个元素
//     abstract public void next(void)

//     // 返回到迭代器的第一个元素
//     abstract public void rewind(void)

//     // 检查当前位置是否有效
//     abstract public boolean valid(void)
// }

// 生成器
// ==================
// Generator implements Iterator {
//     public mixed current(void)
//     public mixed key(void)
//     public void next(void)
//     public void rewind(void)
//     // 向生成器传入一个值
//     public mixed send(mixed $value)
//     public void throw(Exception $exception)
//     public bool valid(void)
//     // 序列化回调
//     public void __wakeup(void)
// }



