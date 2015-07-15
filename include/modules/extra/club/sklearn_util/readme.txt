生成技能学习界面的模块

调用get_skilllearn_table需要提供一个自己技能模块提供的函数名（例如'\\skill53\\sklearn_checker53'），用于回答询问

询问有以下几个：
caller_id：应当返回自身技能编号（如53）
show_cost：是否显示“消耗”栏
query_cost [编号]：如果show_cost返回了是，则这里应当返回消耗栏要显示的内容
is_learnable [编号]：返回该编号技能是否可以被学习，返回否的技能不会出现在技能面板上
now_learnable [编号]：返回该编号技能是否现在已经满足学习要求，返回是则“学习”按钮将可以点

