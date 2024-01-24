# 技能基础功能模块
本模块（skillbase）实现了技能的基础功能函数，包括载入、查询、获得、失去技能等

## 模块变量
本模块有4个模块变量：

### $ppid
判定玩家技能是否经过初始化的变量。是全局变量。
初始为-1，会在玩家技能数据载入后被赋值成`$sdata['pid]`的值，并参与判定某些角色标准数组是否属于玩家。

### $acquired_list
玩家的已获得技能列表。是全局变量。
对应`$sdata['acquired_list']`但并不是引用关系；在`\skillbase\skillbase_load()`时转义并赋值，在`\skillbase\skillbase_save()`时写回到`$sdata`。
已获得技能列表以转义形式储存在player表的nskill字段中，转义后在进程中是类似于`[10 => 1, 11 => 1]`这样的数组格式。
注意：查询玩家是否拥有`$skillid`号技能，请用`\skillbase\skill_query($skillid, $sdata)`，而不要直接对数组进行操作（`$sdata`可省略）。

### $parameter_list
玩家的技能参数列表。是全局变量。
对应`$sdata['parameter_list']`但并不是引用关系；在`\skillbase\skillbase_load()`时赋值，在`\skillbase\skillbase_save()`时写回到`$sdata`。
技能参数列表以转义形式储存在player表的nskillpara字段中，转义后在进程中是类似于`['10_lvl' => 0, '1003_learnedsongs' => '1_2']`这样的数组格式。
注意：查询玩家的`$skillid`号技能的`$skillkey`参数的值，可以用`\skillbase\skill_getvalue($skillid, $skillkey, $sdata)`，而不要直接对数组进行操作（`$sdata`可省略）。
如果需要查询没有经过转义的角色数据的技能情况，可以用`\skillbase\skill_getvalue_direct($skillid, $skillkey, $paradata)`函数。

### $valid_skills
玩家入场时会获得的技能和参数列表。
本模块是空的，其他模块（如部分游戏模式）会操作这个变量。

## 常用模块函数
本模块常用的函数有以下：

### skill_acquire($skillid, &$pa = NULL, $no_cover = 0)
使指定角色获得指定编号的技能。
会自动执行该技能模块的`acquireXXX()`函数。如非必要，不建议继承此函数，而是应该修改`acquireXXX()`函数。
传参：`$skillid`为技能编号；`$pa`为角色数据（对应player表的标准角色数组，如果留空则会调用`$sdata`即当前玩家）；`$no_cover`非覆盖，如果为真则在已有这个技能并再次获得技能时，不会再次执行该技能模块的`acquirexxx()`函数。
返回值：无。

### skill_lost($skillid, &$pa = NULL)
使指定角色失去指定编号的技能。
会自动执行该技能模块的`lostXXX()`函数。如非必要，不建议继承此函数，而是应该修改`lostXXX()`函数。
传参：`$skillid`为技能编号；`$pa`为角色数据。
返回值：无。

### skill_query($skillid, &$pa = NULL)
查询指定角色是否拥有指定编号技能。会依次判定`$pa['acquired_list']`的对应编号元素是否非空，并调用`\skillbase\skill_enabled()`判定技能是否启用。如非必要，不建议继承此函数。
传参：`$skillid`为技能编号；`$pa`为角色数据（对应player表的标准角色数组，如果留空则会调用`$sdata`即当前玩家，但查询的实际上是本模块下的`$acquired_list`数组的情况）
返回值：boolean，true为拥有并已解锁，false为没有或者未解锁。

### skill_enable($skillid, &$pa)
查询指定角色的技能是否启用，正常情况返回true。注意“启用”不同于“解锁”，这里用于实现“禁止某角色使用技能”、“让某角色的技能失效”这样的效果。
实现方式实际上是由需要的模块来继承这个函数并作实现，条件可能各不相同。可以继承此函数。
函数内部有有一个`\skillbase\skill_enabled_core()`函数，禁用请继承`skill_enabled_core()`，要保证启用请继承`skill_enabled()`
传参：`$skillid`为技能编号；`$pa`为角色数据。注意这里不能为空，代码原本就如此，后续可能会整体改掉。
返回值：boolean，true为解锁，false为未解锁。

### skill_setvalue($skillid, $skillkey, $skillvalue, &$pa = NULL)
为指定角色的指定技能的指定参数设置参数值。如非必要，不建议继承此函数。
传参：`$skillid`为技能编号；`$skillkey`为参数名；`$skillvalue`为参数值；`$pa`为角色数据（对应player表的标准角色数组，如果留空则会调用`$sdata`即当前玩家，但修改的实际上是本模块下的`$parameter_list`数组的情况）
参数名和参数值支持字符串，不支持数组和对象，后两类数据请自行转义之后传入。
返回值：无。

### skill_getvalue($skillid, $skillkey, &$pa = NULL)
查询指定角色的指定技能的指定参数的参数值。如非必要，不建议继承此函数。
传参：`$skillid`为技能编号；`$skillkey`为参数名；`$pa`为角色数据（对应player表的标准角色数组，如果留空则会调用`$sdata`即当前玩家，但查询的实际上是本模块下的`$parameter_list`数组的情况）
返回值：查询参数的具体值。

### skill_delvalue($skillid, $skillkey, &$pa = NULL)
删除指定角色的指定技能的指定参数的参数值。如非必要，不建议继承此函数。
某些技能失去时会调用这个函数来删除已经记录的参数值。注意并不是每个技能模块都需要删除参数值，如复活技能用于记录角色是否已经消耗了复活次数，此时可以考虑就算失去技能也不删除参数值，防止玩家利用某些效果反复刷新复活次数。
传参：`$skillid`为技能编号；`$skillkey`为参数名；`$skillvalue`为参数值；`$pa`为角色数据（对应player表的标准角色数组，如果留空则会调用`$sdata`即当前玩家，但修改的实际上是本模块下的`$parameter_list`数组的情况）
返回值：无。

### skill_onload_event(&$pa)
角色数据`$pa`被建立时会被调用的事件函数。本模块为空的，其他模块需要时可以继承这个函数。
这个函数的调用顺序实际上是`\player\fetch_playerdata()`等函数-->`\player\playerdata_construct_process()`或`\player\load_playerdata()`-->`\skillbase\skillbase_load()`-->`skill_onload_event()`。

### skill_onsave_event(&$pa)
角色数据`$pa`被保存时会被调用的事件函数。本模块为空的，其他模块需要时可以继承这个函数。
这个函数的调用顺序实际上是`\player\player_save()`-->`\skillbase\skillbase_save()`-->`\skillbase\skill_onsave_event()`。

### skill_getvalue_direct($skillid, $skillkey, $paradata)
直接查询指定角色的指定技能的指定参数的参数值。
与`\skillbase\skill_getvalue()`函数类似，区别在于，本函数是直接从角色数组的nskillpara元素中读出某个元素的值，不需要通过`\skillbase\skillbase_load()`初始化player结构。

## 技能模块范例

技能的统一代码模板大概是这样的

	比如编号为123的技能
	
	namespace名应该是skill123
	
	必须提供2个函数function acquire123(&$pa) 和 function lost123(&$pa) 
	分别代表获得技能123和失去技能123时候做的事情，$pa不会为NULL

\skillbase\skill_acquire会自动调用acquire技能号($pa)
\skillbase\skill_lost会自动调用lost技能号($pa)

所有技能的自己的私有函数都应该以自己的技能编号为后缀，防止冲突！

示例：

	“受伤属性”的实现：（临时性技能）
		
		接管skill_onload_event函数，判断此人是否受伤，如果有，调用\skillbase\skill_acquire(123,$pa)
		
		接管对应函数，如果此人有这个技能（即受了伤）就执行相应效果
		
		接管skill_onsave_event这个函数，判断如果此技能存在则失去此技能（调用\skillbase\skill_lost(123,$pa)）
		
		这是因为“受伤属性”是非持久的（很容易就治好了），所以在载入玩家时候判定获得，保存玩家时候自动失去
		（如果不失去就会出现即使玩家治好了伤技能却没消失的情况，
		当然一个等价的做法是接管所有能治疗伤口的函数去判，但这是不可行的，因为需要接管的东西太多了，故不采用）
		
	“称号技能”的实现：（持久性技能）
		
		技能的获得和失去由club模块处理，在选称号时调用\skillbase\skill_acquire(123,$pa)，在因某些原因失去称号时调用\skillbase\skill_acquire(123,$pa)
		
		
