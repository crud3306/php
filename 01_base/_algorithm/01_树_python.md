

树的DFS、BFS python版
----------------

```python
class Node(object):
    """节点"""
    def __init__(self, item):
        self.item = item
        self.lchild = None
        self.rchild = None


class BinaryTree(object):
    """二叉树"""
    def __init__(self, node=None):
        self.root = node

    def add(self, item):
        """"""
        if self.root is None:
            self.root = Node(item)
        else:
            queue = []
            queue.append(self.root)
            while len(queue) > 0:
                node = queue.pop(0)
                if not node.lchild:
                    node.lchild = Node(item)
                    return
                else:
                    queue.append(node.lchild)
                if not node.rchild:
                    node.rchild = Node(item)
                    return
                else:
                    queue.append(node.rchild)

    def breadth_travel(self):
        """广度优先遍历"""
        if self.root is None:
            return
        else:
            queue = []
            queue.append(self.root)
            while len(queue) > 0:
                node = queue.pop(0)
                print(node.item, end=" ")
                if node.lchild:
                    queue.append(node.lchild)
                if node.rchild:
                    queue.append(node.rchild)

    def preorder_travel(self, node):
        """根 左 右"""
        if node:
            print(node.item, end=" ")
            self.preorder_travel(node.lchild)
            self.preorder_travel(node.rchild)
        else:
            return

    def inorder_travel(self, node):
        """左 根  右"""
        if node:
            self.inorder_travel(node.lchild)
            print(node.item, end=" ")
            self.inorder_travel(node.rchild)
        else:
            return

    def postorder_travel(self, node):
        """左 右 根"""
        if node:
            self.postorder_travel(node.lchild)
            self.postorder_travel(node.rchild)
            print(node.item, end=" ")
        else:
            return

#测试
if __name__ == '__main__':
    t = BinaryTree()
    t.add(0)
    t.add(1)
    t.add(2)
    t.add(3)
    t.add(4)
    t.add(5)
    t.add(6)
    t.add(7)
    t.add(8)
    t.add(9)
    t.breadth_travel()
    print("")
    t.preorder_travel(t.root)
    print("")
    t.inorder_travel(t.root)
    print("")
    t.postorder_travel(t.root)
    print("")
```
