

二叉树的深度优先遍历（DFS）与广度优先遍历（BFS）
===========
深度优先搜索(Depth First Search)是沿着树的深度遍历树的节点，尽可能深的搜索树的分支。
那么深度遍历有重要的三种方法。这三种方式常被用于访问树的节点，它们之间的不同在于访问每个节点的次序不同。这三种遍历分别叫做先序遍历（preorder），中序遍历（inorder）和后序遍历（postorder）。我们来给出它们的详细定义，然后举例看看它们的应用。

广度优先遍历（BFS）：
从树的root开始，从上到下从从左到右遍历整个树的节点

二叉树深度遍历之PHP实现
-----------
递归版
```php
<?php
class Node {
    public $left = null;
    public $right = null;
    public $value = null;
}
$a = new Node();
$b = new Node();
$c = new NOde();
$d = new NOde();
$e = new NOde();
$a->value = 'a';
$b->value = 'b';
$c->value = 'c';
$d->value = 'd';
$e->value = 'e';
$a->left = $b;
$a->right = $c;
$b->left = $d;
$b->right = $e;

//先序遍历 【根左右】
function outputBefore($a)
{
    if ($a->value != null) {
        echo $a->value.'----';
    } else {
        return ;
    }
    
    if ($a->left != null) {
        outputBefore($a->left);
    }
    if ($a->right !=null ) {
        outputBefore($a->right);
    }
}

//中序遍历：遍历顺序规则为【左根右】
function outputCenter($a)
{
    if ($a->left != null) {
        outputCenter($a->left);
    }
    
    if ($a->value != null) {
        echo $a->value.'----';
    } else {
        return ;
    }
    if ($a->right !=null ) {
        outputCenter($a->right);
    }
}

//后序遍历：遍历顺序规则为【左右根】
function outputLast($a)
{
    if ($a->left != null) {
        outputLast($a->left);
    }
    if ($a->right !=null ) {
        outputLast($a->right);
    }
    if ($a->value != null) {
        echo $a->value.'----';
    } else {
        return ;
    }
}
outputLast($a);
// outputCenter($a);
// outputBefore($a);
```

树的遍历，非递归版
```php
//先序遍历：利用栈先进后出的特性，先访问根节点，再把右子树压入，再压入左子树。这样取出的时候是先取出左子树，最后取出右子树。
function preorder($root){
    $stack  = array();
    array_push($stack, $root);
    while(!empty($stack)){
        $center_node = array_pop($stack);
        echo $center_node->value; // 根节点
        if($center_node->right != null)
            array_push($stack, $center_node->right); // 压入右子树
        if($center_node->left != null)
            array_push($stack, $center_node->left); // 压入左子树
    }
}


//中序：需要从下向上遍历，所以先把左子树压入栈，然后逐个访问根节点和右子树。
function inorder($root){
    $stack = array();
    $center_node = $root;
    while(!empty($stack) || $center_node != null){
        while($center_node != null){
            array_push($stack, $center_node);
            $center_node = $center_node->left;
        }

        $center_node = array_pop($stack);
        echo $center_node->value;

        $center_node = $center_node->right;
    }
}


//后序：先把根节点存起来，然后依次储存左子树和右子树。然后输出。
function tailorder($root){
    $stack = array();
    $outstack = array();
    array_push($stack, $root);
    while(!empty($stack)){
        $center_node = array_pop($stack);
        array_push($outstack, $center_node);
        if($center_node->right != null)
            array_push($stack, $center_node->right);
        if($center_node->left != null)
            array_push($stack, $center_node->left);
    }

    while(!empty($outstack)){
        $center_node = array_pop($outstack);
        echo $center_node->value;
    }
}

```

二叉树广度优先遍历（BFS）：
---------
```php
/*
例如输入

     8
  /     \
 6       10
/  \    /  \
5   7  9   11    

输出：8 6 10 5 7 9 11

思路： 每一层的值从左往右，依次放置一个数组中，然后foreach遍历输出。
*/
<?php
class Node{
    public $value = '';
    public $left,$right;
    public function __construct($value)
    {
        $this->value = $value;
    }
    public function setChildren($left,$right)
    {
        $this->left = $left;
        $this->right = $right;
    }
    public function getValue()
    {
        return $this->value;
    }

    public function getLeft()
    {
        return $this->left;
    }

     public function getRight()
    {
        return $this->right;
    }

}

$A = new Node(8);
$B = new Node(6);
$C = new Node(10);
$D = new Node(5);
$E = new Node(7);
$F = new Node(9);
$G = new Node(11);
$A->setChildren($B,$C);
$B->setChildren($D,$E);
$C->setChildren($F,$G);

$list = [$A];
while (!empty($list)){ 
    $newNodeS = [];  //初始化为空 避免为空
    foreach ($list as $Node) {
        echo $Node->getValue().' ';

        if(!empty($Node->getLeft())) {
            $newNodeS[] = $Node->getLeft();
        }

        if(!empty($Node->getRight())) {
            $newNodeS[] = $Node->getRight();
        }       
    }

    $list = $newNodeS;
}

exit;
```


二叉树翻转之PHP实现
---------
思路：输入根节点，将根节点的左右节点翻转，再递归左右节点，退出条件，输入节点为空
```php
<?php
class Node
{
    public $left = null;
    public $right = null;
    public $value = null;
}
$a = new Node();
$b = new Node();
$c = new Node();
$d = new Node();
$e = new Node();
$a->value = 'a';
$b->value = 'b';
$c->value = 'c';
$d->value = 'd';
$e->value = 'e';
$a->left = $b;
$a->right = $c;
$b->left = $d;
$b->right = $e;

function reverse(&$a)
{
    if ($a == null ) {
        return ;
    }

    $rightTmp = $a->right;
    $a->right = $a->left;
    $a->left = $rightTmp;

    reverse($a->left);
    reverse($a->right);
}
reverse($a);
```

非递归版本用栈来实现，到访问到头节点的时候，将其左子树和右子树互换即可。
```
void swapTree(TreeNode *&root){
    TreeNode *tmp = root->left;
    root->left = root->right;
    root->right = tmp;
}
void invertBinaryTree(TreeNode *root) {
    // write your code here
    if(root == NULL)
        return;
    stack<TreeNode*> stk;
    stk.push(root);
    while(!stk.empty())
    {
        TreeNode *tmp = stk.top();
        stk.pop();
        swapTree(tmp);
        if(tmp->left)
            stk.push(tmp->left);
        if(tmp->right)
            stk.push(tmp->right);
    }
}
```



