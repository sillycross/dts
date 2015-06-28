物品相关基本函数。 

关于物品类型和属性的代码规范：

请在各个模块里赋值，但是在itemmain.resource.config.php里用注释写上哪些物品类型/属性名被你的模块占用了

关于新属性：
鉴于字母已经基本用完，新属性应该全部命名为“^数字^”的形式，其中数字可以任意
例： ^233^ => '防拳' 

\itemmain\parse_itmk_words(物品类型名) 
	获取物品类型的文字描述
	iteminfo里的定义名是物品类型名的前缀即视为匹配，如有多个匹配类型返回定义名最长的那个的名字
	
\itemmain\parse_itmsk_words(属性串, [simple = 0]) 
	获取属性的文字描述，默认（simple=0）以“+”分隔各个属性
	如不需要加号分隔请把simple参数设为1即可
	
\itemmain\count_itmsk_num(属性串)
	计数物品属性个数

\itemmain\get_itmsk_array(属性串)
	以数组形式返回各个属性
	
