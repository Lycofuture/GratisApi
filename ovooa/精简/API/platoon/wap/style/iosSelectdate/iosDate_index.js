/**引入前需初始化几个显示容器
需要配合iosSelect.css
showDateDom 根据阴阳选择实际显示时间的容器id或class
selectDateDom 根据阴阳选择实际显示时间的容器id或class
glDateDom隐藏记录阳历格式化时间的id or class**/
//

// 初始化时间
			var selectDateType = 1;	//gl 2农历		
			var numFlag = '8月';
			var bigNum = '一|二|三|四|五|六|七|八|九|十|冬|腊';
			//var calendarConverter = new Calendar();
			var selectDateDom=$('.selectdate');
			var showDateDom=selectDateDom.find('.datetime');
			var glDateDom=selectDateDom.find('.gl_birthday');
			var nowd=new Date();
			showDateDom.attr('data-year',nowd.getFullYear());
			showDateDom.attr('data-month',nowd.getMonth()+1);
			showDateDom.attr('data-date',nowd.getDate());
			showDateDom.attr('data-hour',nowd.getHours());
			showDateDom.attr('data-minue',nowd.getMinutes());
			//年、月、日、生辰
			var yearData = function(callback) {
				callback(selectDateType == 1 ? Calendar.initNormalCalendar() : Calendar.initLunarCalendar());
			}
			var monthData = function(year, callback) {
				callback(selectDateType == 1 ? Calendar.getMonths(year) : Calendar.getLunarMonths(year));
			};
			var dateData = function(year, month, callback) {
				callback(selectDateType == 1 ? Calendar.getDayCount(month) : Calendar.getLunarDayCount(year,month))
			};
			//var timeData = Calendar.getTime();如果时辰用1~2:59分丑时
			var hourData = Calendar.getHour();
			var minueData = Calendar.getMinue();
			
			// 请选择出生日期
			selectDateDom.bind('click', function() {
				/*showDateDom=selectDateDom.find('.datetime');
				glDateDom=selectDateDom.find('.gl_birthday');*/
				initCalendar();
			})
			
			//创建日期表 
			function initCalendar() {
				// 若是农历则转化成阳历
				if (bigNum.indexOf(numFlag.substr(0, 1)) >= 0) {
					var toSolarObj = Lunar.toSolar(showDateDom.attr('data-year'), showDateDom.attr('data-month'), showDateDom.attr('data-date'));
					var oneLevelId = toSolarObj[0];
					var twoLevelId = toSolarObj[1];
					var threeLevelId = toSolarObj[2];
					//var fourLevelId = showDateDom.attr('data-time');
					var fourLevelId = showDateDom.attr('data-hour');
					var fiveLevelId = showDateDom.attr('data-minue');
					// 更新此时状态
					showDateDom.attr('data-year', toSolarObj[0]);
					showDateDom.attr('data-month', toSolarObj[1]);
					showDateDom.attr('data-date', toSolarObj[2]);
					numFlag = toSolarObj[1] + "月";
				} else {
					// 若是阳历则不转化
					var oneLevelId = showDateDom.attr('data-year');
					var twoLevelId = showDateDom.attr('data-month');
					var threeLevelId = showDateDom.attr('data-date');
					//var fourLevelId = showDateDom.attr('data-time');
					var fourLevelId = showDateDom.attr('data-hour');
					var fiveLevelId = showDateDom.attr('data-minue');
				}
				createCalendar(oneLevelId, twoLevelId, threeLevelId, fourLevelId,fiveLevelId, "active");
			}
			// 农历阳历切换
			function changeEvent(obj, num) {
				
				if (selectDateType == num) return;
				
				selectDateType = num;
				
				// 不能同时存在两个iosselect组件
				$('.olay').remove();
				
				if (selectDateType == 1) {
					
					initCalendar();
					
					$("." + obj.className).eq(num - 1).addClass("actice").siblings().removeClass("active");
				} else {
					// 若是阳历则转化成农历
					if (bigNum.indexOf(numFlag.substr(0, 1)) < 0) {
						var toLunarObj = Lunar.toLunar(showDateDom.attr('data-year'), showDateDom.attr('data-month'), showDateDom.attr('data-date'));
						var oneLevelId = toLunarObj[0];
						var twoLevelId = toLunarObj[1];
						var threeLevelId = toLunarObj[2];
						var fourLevelId = showDateDom.attr('data-hour');
						var fiveLevelId = showDateDom.attr('data-minue');
						showDateDom.attr('data-year', toLunarObj[0]);
						showDateDom.attr('data-month', toLunarObj[1]);
						showDateDom.attr('data-date', toLunarObj[2])
						numFlag = toLunarObj[5].substr(-2, 2);
					} else {
						// 若是农历则不转化
						var oneLevelId = showDateDom.attr('data-year');
						var twoLevelId = showDateDom.attr('data-month');
						var threeLevelId = showDateDom.attr('data-date');
						var fourLevelId = showDateDom.attr('data-hour');
						var fiveLevelId = showDateDom.attr('data-minue');
					}
					// 切换成农历时,需要重新创建组件
					createCalendar(oneLevelId, twoLevelId, threeLevelId, fourLevelId, fiveLevelId, "active");
					$("." + obj.className).eq(num - 1).addClass("active").siblings().removeClass("active");
				}
			}
			/*
			function returnEvent() {
				selectDateType = 1;
				initCalendar();
				$(".sure-wrapper").hide();
			}

			function sureEvent() {
				selectDateType = 1;
				$(".sure-wrapper").hide();
			}*/
	
			function createCalendar(oneLevelId, twoLevelId, threeLevelId, fourLevelId, fiveLevelId, activeClassName) {
				var flag = selectDateType === 1;
				var title = '<div><span class="' + (flag ? activeClassName : "") +
					' calendar" onclick="changeEvent(this,1)">公历</span><span class="calendar ' + (!flag ? activeClassName : "") +
					'" onclick="changeEvent(this,2)">农历</span></div>';
				// 创建组件 5 栏
				var iosSelect = new IosSelect(5,
					[yearData, monthData, dateData, hourData,minueData], {
						title: title,
						headerHeight: 64,
						itemHeight: 50,
						itemShowCount: 5,
						oneLevelId: oneLevelId,
						twoLevelId: twoLevelId,
						threeLevelId: threeLevelId,
						fourLevelId: fourLevelId,
						fiveLevelId: fiveLevelId,
						callback: function(selectOneObj, selectTwoObj, selectThreeObj, selectFourObj,selectFiveObj) {
							showDateDom.attr('data-year', selectOneObj.id);
							showDateDom.attr('data-month', selectTwoObj.id);
							showDateDom.attr('data-date', selectThreeObj.id);
							showDateDom.attr('data-hour', selectFourObj.id);
							showDateDom.attr('data-minue', selectFiveObj.id);
							$(".sure-wrapper").show();
							if (selectDateType == 1) {
								var toLunarObj = Lunar.toLunar(selectOneObj.id, selectTwoObj.id, selectThreeObj.id);
								$(".solar-time").html(selectOneObj.value + selectTwoObj.value + selectThreeObj.value + ' ' + selectFourObj.value + selectFiveObj.value);//1-2:59用selectFourObj.name
								$(".lunar-time").html(toLunarObj[0] + '年' + toLunarObj[5] + toLunarObj[6] + ' ' + selectFourObj.value+ selectFiveObj.value);
								glDateDom.val(selectOneObj.id +'-'+ selectTwoObj.id +'-'+ selectThreeObj.id + ' ' + selectFourObj.id+':'+ selectFiveObj.id);
							} else {
								var toSolarObj = Lunar.toSolar(selectOneObj.id, selectTwoObj.id, selectThreeObj.id)
								$(".lunar-time").html(selectOneObj.value + selectTwoObj.value + selectThreeObj.value + ' ' + selectFourObj.value+ selectFiveObj.value);
								$(".solar-time").html(toSolarObj[0] + "年" + toSolarObj[1] + "月" + toSolarObj[2] + "日" + ' ' + selectFourObj.value+ selectFiveObj.value);
								glDateDom.val(toSolarObj[0] +'-'+ toSolarObj[1] +'-'+ toSolarObj[2] + ' ' + selectFourObj.id+':'+ selectFiveObj.id);
							}
							showDateDom.val(selectOneObj.value + selectTwoObj.value + selectThreeObj.value + ' ' + selectFourObj.value+ selectFiveObj.value);
							
						}
					});
			}