<?php

$arr=array(49,38,65,97,76,13,27,50);
$arrSize=count($arr);

//将第一次排序抽出来，因为最后一次排序不需要再交换值了。
buildHeap($arr, $arrSize);

for($i=$arrSize-1; $i>0; $i--){
    swap($arr,$i,0);
    $arrSize--;
    buildHeap($arr,$arrSize);   
}

var_dump($arr);

//用数组建立最小堆
function buildHeap(&$arr,$arrSize){
    //计算出最开始的下标$index,如图,为数字"97"所在位置,比较每一个子树的父结点和子结点,将最小值存入父结点中
    //从$index处对一个树进行循环比较,形成最小堆
    for($index=intval($arrSize/2)-1; $index>=0; $index--){
        //如果有左节点,将其下标存进最小值$min
        if($index*2+1 < $arrSize){
            $min=$index*2+1;

            //如果有右子结点,比较左右结点的大小,如果右子结点更小,将其结点的下标记录进最小值$min
            if($index*2+2<$arrSize){
                if($arr[$index*2+2]<$arr[$min]){
                    $min=$index*2+2;
                }
            }

            //将子结点中较小的和父结点比较,若子结点较小,与父结点交换位置,同时更新较小
            if($arr[$min]<$arr[$index]){
                swap($arr,$min,$index);
            }   
        }
    }
}

//此函数用来交换下数组$arr中下标为$one和$another的数据
function swap(&$arr,$one,$another){
    $tmp=$arr[$one];
    $arr[$one]=$arr[$another];
    $arr[$another]=$tmp;
}