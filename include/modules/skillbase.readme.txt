各类技能的基石

skill_onload_event(&$pa) 从数据库载入玩家$pa时这个函数会被调用，$pa不会为NULL
skill_onsave_event(&$pa) 保存玩家$pa到数据库时这个函数会被调用

skill_acquire(技能号,&$pa = NULL) 登记技能获得，不带$pa参数或设$pa参数为NULL则代表是当前玩家，下同
skill_query(技能号,&$pa = NULL) 询问技能是否获得
skill_lost(技能号,&$pa = NULL) 登记技能失去

skill_setvalue(技能号,键,值,&$pa = NULL) 保存值（值是记在数据库里的，永久性的）
skill_getvalue(技能号,键,&$pa = NULL) 获取值
skill_delvalue(技能号,键,&$pa = NULL) 删除值

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
		
		
