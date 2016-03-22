# lib_reducer -> Hadoop之MapReduce程序之Reducer框架(PHP,Python)  

### 主要特性：模仿Java的Reducer基类，提供迭代器版本的values，可使用for遍历某key对应的values各值。  ###

**示例1：**

    <?php
	require_once("libreducer.php");

	class MyReducer extends BaseReducer{		
		public function reduce($key, $values){
			foreach($values as $value){
				echo "key: " . $key."\tvalue: " . $value . "\n";
			} 					
		}
	}

	run(new MyReducer());
输入（cat test_input | php example.php）：  

    a
	a	2	8
	b	3

	c
	c	5

输出：

    key: a  value:
	key: a  value: 2        8
	key: b  value: 3
	key: c  value:
	key: c  value: 5
**示例2：**

    <?php
	require_once("libreducer.php");

	class MyReducer extends BaseReducer{		
		public function reduce($key, $values){
			echo "key: " . $key . "\n"; 					
		}
	}

	run(new MyReducer());
输入同上，输出：

	key: a
	key: b
	key: c
**示例3：**  
通过重载BaseReducer类的parse函数可以自定义解析key,value的方法，参数为一行文本字符串，返回array($key, $value)。  
默认实现是以"\t"作为分隔符，第一列为key，其余为value

    <?php
	require_once("libreducer.php");

	class MyReducer extends BaseReducer{		
		public function parse($line){
			list($k, $v) = parent::parse($line);
			return array($k . "k", $v . "v");//自定义key,value的输出
		}		
		public function reduce($key, $values){
			foreach($values as $value){
				echo "key: " . $key."\tvalue: " . $value . "\n";
			}  					
		}
	}

	run(new MyReducer());
输入同上，输出：

	key: ak value: v
	key: ak value: 2        8v
	key: bk value: 3v
	key: ck value: v
	key: ck value: 5v
