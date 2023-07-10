String.prototype.parseToArray = function(bit, s) {
	var ret = this.split(s || "|");
	return bit ? function(l, n) {
		for (; l--;) ret[l] = parseInt(ret[l], bit);
		return ret;
	}(ret.length) : ret;
}
	var map = {
	//公历天数集合
	days: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
	//公历节日
	feast: {
		"1-1": "元旦节",
		"2-14": "情人节",
		"3-5": "雷锋日",
		"3-8": "妇女节",
		"3-12": "植树节",
		"3-15": "消费日",
		"4-1": "愚人节",
		"5-1": "劳动节",
		"5-4": "青年节",
		"6-1": "儿童节",
		"7-1": "建党节",
		"8-1": "建军节",
		"9-10": "教师节",
		"10-1": "国庆节",
		"12-24": "平安夜",
		"12-25": "圣诞节"
	},

	//农历
	lunar: {
		//template
		tpl: "#{y}-#{m}-#{d} 星期#{W} 农历 #{CM}#{CD} #{gy}(#{sx}) #{gm} #{gd} #{so} #{cf} #{gf}",

		//闰月: leap[y-1900] & 0xf，闰月天数: leap[y-1900] & 0x10000
		leap: "ezc|esg|wog|gr9|15k0|16xc|1yl0|h40|ukw|gya|esg|wqe|wk0|15jk|2k45|zsw|16e8|yaq|tkg|1t2v|ei8|wj4|zp1|l00|lkw|2ces|8kg|tio|gdu|ei8|k12|1600|1aa8|lud|hxs|8kg|257n|t0g|2i8n|13rk|1600|2ld2|ztc|h40|2bas|7gw|t00|15ma|xg0|ztj|lgg|ztc|1v11|fc0|wr4|1sab|gcw|xig|1a34|l28|yhy|xu8|ew0|xr8|wog|g9s|1bvn|16xc|i1j|h40|tsg|fdh|es0|wk0|161g|15jk|1654|zsw|zvk|284m|tkg|ek0|xh0|wj4|z96|l00|lkw|yme|xuo|tio|et1|ei8|jw0|n1f|1aa8|l7c|gxs|xuo|tsl|t0g|13s0|16xg|1600|174g|n6a|h40|xx3|7gw|t00|141h|xg0|zog|10v8|y8g|gyh|exs|wq8|1unq|gc0|xf4|nys|l28|y8g|i1e|ew0|wyu|wkg|15k0|1aat|1640|hwg|nfn|tsg|ezb|es0|wk0|2jsm|15jk|163k|17ph|zvk|h5c|gxe|ek0|won|wj4|xn4|2dsl|lk0|yao"
			.parseToArray(36),

		//节气
		jqmap: "0|gd4|wrn|1d98|1tuh|2akm|2rfn|38g9|3plp|46vz|4o9k|55px|5n73|64o5|6m37|73fd|7kna|81qe|8io7|8zgq|9g4b|9wnk|ad3g|ath2|"
			.parseToArray(36),
		jqnames: "小寒|大寒|立春|雨水|惊蛰|春分|清明|谷雨|立夏|小满|芒种|夏至|小暑|大暑|立秋|处暑|白露|秋分|寒露|霜降|立冬|小雪|大雪|冬至".parseToArray(),

		//中文数字
		c1: "一|二|三|四|五|六|七|八|九|十".parseToArray(),
		c2: "初|十|廿|卅|".parseToArray(),

		//中文星期
		wk: "日一二三四五六",

		//天干
		tg: "癸甲乙丙丁戊己庚辛壬",

		//地支
		dz: "亥子丑寅卯辰巳午未申酉戌",

		//生肖
		sx: "鼠牛虎兔龙蛇马羊猴鸡狗猪",

		//农历节日
		feast: {
			"1-1": "春节",
			"1-15": "元宵节",
			"5-5": "端午节",
			"8-15": "中秋节",
			"9-9": "重阳节",
			"12-8": "腊八节"
		},

		// 日期修正数组
		// ~表示日期范围
		// = 前面是日期, 后面对应的分别是年月日的修正值
		// 例: fixDate: ["2013-1-1=0|-1|1", "2013-1-12~2013-2-9=0|-1|0"]
		fixDate: ["2013-1-1~2013-1-11=0|-1|1", "2013-1-12~2013-2-9=0|-1|0"]
		}
	};
var MAX_LUNAR_YEAY = 2050;
var MIN_LUNAR_YEAY = 1900;

var Calendar={

	/**
	 *获取日期
	 *@method: getDate
	 *@param: {Date} || new Date()
	 *@return: {y: 年, m: 月, d: 日}
	 */
	getDate:function(date){
		!this.isDate(date) && (date = new Date());
		return {
			y: date.getFullYear(),
			m: date.getMonth() + 1,
			d: date.getDate()
		};
	},

	/**
  *检查是否date对象
  *@method: isDate
  *@param: {Date}
  *@return: {Bool}
  */
	isDate:function(date){
		return date instanceof Date && !isNaN(date);
	},
	/**
	 *返回返回农历月份天数
	 *@method: getDaysByLunarMonth
	 *@param: {Num} lunar year
	 *@param: {Num} lunar month
	 *@return: {Num}
	 */

	getDaysByLunarMonth:function(y, m) {
		//m = m - 1;
		/*
		var year_count = y - 1900;
		for (let i = 0; i < nlDate.length; i++) {
			if (year_count == i) {
				for (let j = 0; j < nlDate[i].m.length; j++) {
					// data.monthCol.push(nlDate[i].m[j].n)
					if (m == j) {
						if (nlDate[i].m[j].d == 29)
							return 29
						else
							return 30
					}
				}
			}
		}*/
		return Lunar.lunarMonthDays(y,m);
	},
	/**
	 *返回公历年份的闰月月份
	 *@method: getLeapMonth
	 *@param: {Num} year
	 *@return: {Num} || 0
	 */
	getLeapMonth:function(y) {
		return map.lunar.leap[y - 1900] & 0xf;
	},
	/**
	 *根据序号返回干支组合名
	 *@method: cyclical
	 *@param: {Num} 序号 (0 --> 甲子，以60进制循环)
	 *@return: {String}
	 */
	cyclical:function(n) {
		return (map.lunar.tg.charAt(n % 10) + map.lunar.dz.charAt(n % 12));
	},
	initCalendar:function() {
		this.initNormalCalendar();
		this.initLunarCalendar();
	},
	initNormalCalendar:function() {
		var year = [];
		for (var i = MIN_LUNAR_YEAY; i < MAX_LUNAR_YEAY; i++) {
			year.push({
				id: i,
				value: i + '年'
			});
		}
		return year;

	},
	getMonths:function(y) {
		var month = [];

		for (var i = 1; i <= 12; i++) {
			month.push({
				id: i,
				value: i + '月'
			})
		}
		return month;
	},
	getDayCount:function(m) {
		var days = [];
		for (var i = 1; i <= map.days[m - 1]; i++) {
			days.push({
				id: i,
				value: i + '日'
			})
		}
		return days;
	},
	getHour:function() {
		var hours = [];
		for (var i = 0; i <= 23; i++) {
			hours.push({
				id: i,
				value: i + '时'
			})
		}
		return hours;
	},
	getMinue:function() {
		var minues = [];
		for (var i = 1; i <= 59; i++) {
			minues.push({
				id: i,
				value: i + '分'
			})
		}
		return minues;
	},
	/*
	getNtime:function() {
		var time = [{
				id: 1,
				value: "时辰未知",
				name:"时辰未知"
			},
			{
				id: 2,
				value: "00:00-00:59(早子)",
				name:"早子时"
			},
			{
				id: 3,
				value: "01:00-01:59(丑)",
				name:"丑时"
			},
			{
				id: 4,
				value: "02:00-02:59(丑)",
				name:"丑时"
			},
			{
				id: 5,
				value: "03:00-03:59(寅)",
				name:"寅时"
			},
			{
				id: 6,
				value: "04:00-04:59(寅)",
				name:"寅时"
			},
			{
				id: 7,
				value: "05:00-05:59(卯)",
				name:"卯时"
			},
			{
				id: 8,
				value: "06:00-06:59(卯)",
				name:"卯时"
			},
			{
				id: 9,
				value: "07:00-07:59(辰)",
				name:"辰时"
			},
			{
				id: 10,
				value: "08:00-08:59(辰)",
				name:"辰时"
			},
			{
				id: 11,
				value: "09:00-09:59(巳)",
				name:"巳时"
			},
			{
				id: 12,
				value: "10:00-10:59(巳)",
				name:"巳时"
			},
			{
				id: 13,
				value: "11:00-11:59(午)",
				name:"午时"
			},
			{
				id: 14,
				value: "12:00-12:59(午)",
				name:"午时"
			},
			{
				id: 15,
				value: "13:00-13:59(未)",
				name:"未时"
			},
			{
				id: 16,
				value: "14:00-14:59(未)",
				name:"未时"
			},
			{
				id: 17,
				value: "15:00-15:59(申)",
				name:"申时"
			},
			{
				id: 18,
				value: "16:00-16:59(申)",
				name:"申时"
			},
			{
				id: 19,
				value: "17:00-17:59(酉)",
				name:"酉时"
			},
			{
				id: 20,
				value: "18:00-18:59(酉)",
				name:"酉时"
			},
			{
				id: 21,
				value: "19:00-19:59(戌)",
				name:"戌时"
			},
			{
				id: 22,
				value: "20:00-20:59(戌)",
				name:"戌时"
			},
			{
				id: 23,
				value: "21:00-21:59(亥)",
				name:"亥时"
			},
			{
				id: 24,
				value: "22:00-22:59(亥)",
				name:"亥时"
			},
			{
				id: 25,
				value: "23:00-23:59(晚子)",
				name:"晚子"
			},
		];
		return time;
	},*/
	initLunarCalendar:function() {
		var year = [];
		for (var i = MIN_LUNAR_YEAY; i < MAX_LUNAR_YEAY; i++) {
			year.push({
				id: i,
				value: i + '年'
			});
		}
		return year;
	},
	getLunarMonths:function(y) {
		var month = [];
		var c1 = '一|二|三|四|五|六|七|八|九|十|冬|腊'.split('|');
		if(this.getLeapMonth(y) > 0){
			var num = this.getLeapMonth(y);
		}else{
			var num = 13;
		}
		
		for (var i = 1; i <= 12; i++) {
			
			if(i > num ){
				month.push({
					id: i+1,
					value: c1[i - 1] + '月'
				})
				
			}else{
				month.push({
					id: i,
					value: c1[i - 1] + '月'
				})
			}
			
		}
		
		if (num >0 && num < 13) {
			month.splice(num, 0, {
				id: num + 1,
				value: '闰' + c1[num - 1] + '月'
			});
		}
		return month;
	},

	getLunarDayCount:function(y, m) {
		var days = [];
		var num = this.getDaysByLunarMonth(y, m);
		for (var i = 1; i <= num; i++) {
			days.push({
				id: i,
				value: this.getLunarDayName(i)
			})
		}
		return days;
	},
	getLunarDayName:function(day) {
		var a = Math.floor(day / 10);
		return map.lunar.c2[day > 10 ? a : 0] + map.lunar.c1[(day - 1) % 10]
	},

};

var Lunar = {
	selectDateType:2,
    MIN_YEAR : 1891,
    MAX_YEAR : 2100,
    lunarInfo : [
        [0,2,9, 21936], [6,1,30, 9656], [0,2,17, 9584], [0,2,6, 21168], [5,1,26,43344], [0,2,13,59728],
        [0,2,2, 27296], [3,1,22,44368], [0,2,10,43856], [8,1,30,19304], [0,2,19,19168], [0,2,8, 42352],
        [5,1,29,21096], [0,2,16,53856], [0,2,4, 55632], [4,1,25,27304], [0,2,13,22176], [0,2,2, 39632],
        [2,1,22,19176], [0,2,10,19168], [6,1,30,42200], [0,2,18,42192], [0,2,6, 53840], [5,1,26,54568],
        [0,2,14,46400], [0,2,3, 54944], [2,1,23,38608], [0,2,11,38320], [7,2,1, 18872], [0,2,20,18800],
        [0,2,8, 42160], [5,1,28,45656], [0,2,16,27216], [0,2,5, 27968], [4,1,24,44456], [0,2,13,11104],
        [0,2,2, 38256], [2,1,23,18808], [0,2,10,18800], [6,1,30,25776], [0,2,17,54432], [0,2,6, 59984],
        [5,1,26,27976], [0,2,14,23248], [0,2,4, 11104], [3,1,24,37744], [0,2,11,37600], [7,1,31,51560],
        [0,2,19,51536], [0,2,8, 54432], [6,1,27,55888], [0,2,15,46416], [0,2,5, 22176], [4,1,25,43736],
        [0,2,13, 9680], [0,2,2, 37584], [2,1,22,51544], [0,2,10,43344], [7,1,29,46248], [0,2,17,27808],
        [0,2,6, 46416], [5,1,27,21928], [0,2,14,19872], [0,2,3, 42416], [3,1,24,21176], [0,2,12,21168],
        [8,1,31,43344], [0,2,18,59728], [0,2,8, 27296], [6,1,28,44368], [0,2,15,43856], [0,2,5, 19296],
        [4,1,25,42352], [0,2,13,42352], [0,2,2, 21088], [3,1,21,59696], [0,2,9, 55632], [7,1,30,23208],
        [0,2,17,22176], [0,2,6, 38608], [5,1,27,19176], [0,2,15,19152], [0,2,3, 42192], [4,1,23,53864],
        [0,2,11,53840], [8,1,31,54568], [0,2,18,46400], [0,2,7, 46752], [6,1,28,38608], [0,2,16,38320],
        [0,2,5, 18864], [4,1,25,42168], [0,2,13,42160], [10,2,2,45656], [0,2,20,27216], [0,2,9, 27968],
        [6,1,29,44448], [0,2,17,43872], [0,2,6, 38256], [5,1,27,18808], [0,2,15,18800], [0,2,4, 25776],
        [3,1,23,27216], [0,2,10,59984], [8,1,31,27432], [0,2,19,23232], [0,2,7, 43872], [5,1,28,37736],
        [0,2,16,37600], [0,2,5, 51552], [4,1,24,54440], [0,2,12,54432], [0,2,1, 55888], [2,1,22,23208],
        [0,2,9, 22176], [7,1,29,43736], [0,2,18, 9680], [0,2,7, 37584], [5,1,26,51544], [0,2,14,43344],
        [0,2,3, 46240], [4,1,23,46416], [0,2,10,44368], [9,1,31,21928], [0,2,19,19360], [0,2,8, 42416],
        [6,1,28,21176], [0,2,16,21168], [0,2,5, 43312], [4,1,25,29864], [0,2,12,27296], [0,2,1, 44368],
        [2,1,22,19880], [0,2,10,19296], [6,1,29,42352], [0,2,17,42208], [0,2,6, 53856], [5,1,26,59696],
        [0,2,13,54576], [0,2,3, 23200], [3,1,23,27472], [0,2,11,38608], [11,1,31,19176],[0,2,19,19152],
        [0,2,8, 42192], [6,1,28,53848], [0,2,15,53840], [0,2,4, 54560], [5,1,24,55968], [0,2,12,46496],
        [0,2,1, 22224], [2,1,22,19160], [0,2,10,18864], [7,1,30,42168], [0,2,17,42160], [0,2,6, 43600],
        [5,1,26,46376], [0,2,14,27936], [0,2,2, 44448], [3,1,23,21936], [0,2,11,37744], [8,2,1, 18808],
        [0,2,19,18800], [0,2,8, 25776], [6,1,28,27216], [0,2,15,59984], [0,2,4, 27424], [4,1,24,43872],
        [0,2,12,43744], [0,2,2, 37600], [3,1,21,51568], [0,2,9, 51552], [7,1,29,54440], [0,2,17,54432],
        [0,2,5, 55888], [5,1,26,23208], [0,2,14,22176], [0,2,3, 42704], [4,1,23,21224], [0,2,11,21200],
        [8,1,31,43352], [0,2,19,43344], [0,2,7, 46240], [6,1,27,46416], [0,2,15,44368], [0,2,5, 21920],
        [4,1,24,42448], [0,2,12,42416], [0,2,2, 21168], [3,1,22,43320], [0,2,9, 26928], [7,1,29,29336],
        [0,2,17,27296], [0,2,6, 44368], [5,1,26,19880], [0,2,14,19296], [0,2,3, 42352], [4,1,24,21104],
        [0,2,10,53856], [8,1,30,59696], [0,2,18,54560], [0,2,7, 55968], [6,1,27,27472], [0,2,15,22224],
        [0,2,5, 19168], [4,1,25,42216], [0,2,12,42192], [0,2,1, 53584], [2,1,21,55592], [0,2,9, 54560]
    ],
    //是否闰年
    isLeapYear : function(year) {
        return ((year % 4 == 0 && year % 100 != 0) || (year % 400 == 0));
    },
    //天干地支年
    lunarYear : function(year) {
        var gan = ['庚', '辛', '壬', '癸', '甲', '乙', '丙', '丁', '戊', '己'],
            zhi = ['申', '酉', '戌', '亥', '子', '丑', '寅', '卯', '辰', '巳', '午', '未'],
            str = year.toString().split("");
        return gan[str[3]] + zhi[year % 12];
    },
    //生肖年
    zodiacYear : function(year) {
        var zodiac = ['猴', '鸡', '狗', '猪', '鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊'];
        return zodiac[year % 12];
    },
    //公历月份天数
    //@param year 阳历-年
    //@param month 阳历-月
    solarMonthDays : function(year, month) {
        var FebDays = this.isLeapYear(year) ? 29 : 28;
        var monthHash = ['', 31, FebDays, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        return monthHash[month];
    },
    //农历月份天数
    lunarMonthDays : function(year, month) {
        var monthData = this.lunarMonths(year);
        return monthData[month - 1];
    },
    //农历月份天数数组
    lunarMonths : function(year) {
        var yearData = this.lunarInfo[year - this.MIN_YEAR];
        var leapMonth = yearData[0];
        var bit = (+yearData[3]).toString(2);
        var months = [];
        for (var i = 0; i < bit.length; i++) {
            months[i] = bit.substr(i, 1);
        }
     
        for (var k = 0, len = 16 - months.length; k < len; k++) {
            months.unshift('0');
        }
     
        months = months.slice(0, (leapMonth == 0 ? 12 : 13));
        for (var i = 0; i < months.length; i++) {
            months[i] = +months[i] + 29;
        }
        return months;
    },
    //农历每年的天数
    //@param year 农历年份
    lunarYearDays : function(year) {
        var monthArray = this.lunarYearMonths(year);
        var len = monthArray.length;
        return (monthArray[len-1] == 0 ? monthArray[len-2] : monthArray[len-1]);
    },
    //
    lunarYearMonths : function(year) {
        var monthData = this.lunarMonths(year);
        var res = [];
        var temp = 0;
        var yearData = this.lunarInfo[year - this.MIN_YEAR];
        var len = (yearData[0] == 0 ? 12 : 13);
        for (var i = 0; i < len; i++) {
            temp = 0;
            for (j = 0; j <= i; j++) {
                temp += monthData[j];
            }
            res.push(temp);
        }
        return res;
    },
    //获取闰月
    //@param year 农历年份
    leapMonth : function(year){
        var yearData = this.lunarInfo[year - this.MIN_YEAR];
        return yearData[0];
    },
    //计算农历日期与正月初一相隔的天数
    betweenLunarDays : function(year, month, day) {
        var yearMonth = this.lunarMonths(year);
        var res = 0;
        for (var i = 1; i < month; i++) {
            res += yearMonth[i-1];
        }
        res += day - 1;
        return res;
    },
    //计算2个阳历日期之间的天数
    //@param year 阳历年
    //@param month
    //@param day
    //@param l_month 阴历正月对应的阳历月份
    //@param l_day   阴历初一对应的阳历天
    betweenSolarDays : function(year, month, day, l_month, l_day) {
        var time1 = new Date(year +"/"+  month  +"/"+ day).getTime(),
            time2 = new Date(year +"/"+ l_month +"/"+ l_day).getTime();
        return Math.ceil((time1-time2)/24/3600/1000);
    },
    //根据距离正月初一的天数计算阴历日期
    //@param year 阳历年
    //@param between 天数
    lunarByBetween : function(year, between) {
        var lunarArray = [], yearMonth = [], t = 0, e = 0, leapMonth = 0, m = '';
        if (between == 0) {
            t = 1;
            e = 1;
            m = '正月';
        } else {
            year = between > 0 ? year : (year - 1);
            yearMonth = this.lunarYearMonths(year);
            leapMonth = this.leapMonth(year);
            between   = between > 0 ? between : (this.lunarYearDays(year) + between);
            for (var i = 0; i < 13; i++) {
                if (between == yearMonth[i]) {
                    t = i + 2;
                    e = 1;
                    break;
                } else if (between < yearMonth[i]) {
                    t = i + 1;
                    e = between - ((yearMonth[i-1]) ? yearMonth[i-1] : 0) + 1;
                    break;
                }
            }

            m = (leapMonth != 0 && t == leapMonth + 1)? ('闰'+this.chineseMonth(t-1)) : this.chineseMonth(((leapMonth != 0 && leapMonth + 1 < t) ? (t - 1) : t));
        }
        lunarArray.push(year, t, e); //年 月 日
        lunarArray.push(this.lunarYear(year),
                        this.zodiacYear(year),
                        m,
                        this.chineseNumber(e)); //天干地支年 生肖年 月份 日
        lunarArray.push(leapMonth); //闰几月
        return lunarArray;
    },
    //中文月份
    chineseMonth : function(month) {
        var monthHash = ['', '正月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '冬月', '腊月'];
        return monthHash[month];
    },
    //中文日期
    chineseNumber : function(num) {
        var dateHash = ['', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十'];
        if (num <= 10) {
            res = '初'+ dateHash[num];
        } else if (num > 10 && num < 20) {
            res = '十'+ dateHash[num-10];
        } else if (num == 20) {
            res = "二十";
        } else if (num > 20 && num < 30) {
            res = "廿"+ dateHash[num-20];
        } else if (num == 30) {
            res = "三十";
        }
        return res;
    },
    //转换农历
    toLunar : function(year, month, day) {
		this.selectDateType = 2;
        var yearData = this.lunarInfo[year - this.MIN_YEAR];
        if (year == this.MIN_YEAR && month <= 2 && day <= 9) {
            return [1891, 1, 1, '辛卯', '兔', '正月', '初一'];
        }
        return this.lunarByBetween(year, this.betweenSolarDays(year, month, day, yearData[1], yearData[2]));
    },
    //转换公历
    //@param year  阴历-年
    //@param month 阴历-月，闰月处理：例如如果当年闰五月，那么第二个五月就传六月，相当于阴历有13个月
    //@param date  阴历-日
    toSolar : function(year, month, day) {
		this.selectDateType = 1;
        var yearData = this.lunarInfo[year - this.MIN_YEAR];
        var between  = this.betweenLunarDays(year, month, day);
        var ms = new Date(year +"/" + yearData[1] +"/"+ yearData[2]).getTime();
        var s = ms + between * 24 * 60 * 60 * 1000;
        var d = new Date();
        d.setTime(s);
        year  = d.getFullYear();
        month = d.getMonth() + 1;
        day   = d.getDate();
        return [year, month, day];
    }
};
