气泡框模块，用于显示一个气泡框。由于jScrollPane特性，如果有滚动条，气泡框内文字段落必须用<p>标签包起来才行。

各气泡框参数和默认值请参见main.php中bubblebox_set_default函数。

使用示例：

<!--{eval bubblebox_start('id:233;height:200;width:500;offset-y:50;');}-->
<p>示例内容</p>
<!--{eval bubblebox_end();}-->
上述模板将生成一个200像素高，500像素宽，居于屏幕中心偏下50像素，ID为233的一个气泡框

生成的气泡框默认是隐藏的，想显示的话只需javascript调用bubblebox_show(气泡框ID)即可。例如bubblebox_show('233');

如果是游戏内js默认不会直接执行，想要自动显示的话需要用onload才行：
<img style="display:none;" type="hidden" src="img/blank.png" onload="bubblebox_show('233');">


