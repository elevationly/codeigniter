var queryConditions = {
	matchCon: ""
},
	SYSTEM = system = parent.SYSTEM,
	hiddenAmount = !1,
	billRequiredCheck = system.billRequiredCheck,
	//billRequiredCheck = 0,
	urlParam = Public.urlParam();
queryConditions.transType = "150502" === urlParam.transType ? "150502" : "150501";
var THISPAGE = {
	init: function() {
		SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_INAMOUNT || (hiddenAmount = !0), this.initDom(), this.loadGrid(), this.addEvent()
	},
	initDom: function() {
		this.$_matchCon = $("#matchCon"),this.$_mname = $("#mname"),this.$_mnumber = $("#mnumber"), this.$_beginDate = $("#beginDate").val(system.beginDate), this.$_endDate = $("#endDate").val(system.endDate), this.$_matchCon.placeholder(), this.$_beginDate.datepicker(), this.$_endDate.datepicker()
	},
	loadGrid: function() {
		function a(a, b, c) {
			var d;
			if(SYSTEM.userName=="admin"){
				d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a></div>';
			}else{
				d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a></div>';
			}
			return d
		}
		var b = Public.setGrid();
		queryConditions.beginDate = this.$_beginDate.val(), queryConditions.endDate = this.$_endDate.val();
		var c = "150501" == queryConditions.transType ? "付" : "退";
			
		$("#grids").jqGrid({
			url: "../scm/invSa/purListmain?action=purListmain",
			postData: queryConditions,
			datatype: "json",
			autowidth: !0,
			height: b.h,
			altRows: !0,
			gridview: !0,
			multiselect: !0,
			colNames: ["操作", "单据日期", "单据编号", "项目名", "总数量", "总金额"/*, "已" + c + "款"*/, "领料人", "制单人", "备注", "订单来源"],
			colModel: [{
				name: "operating",
				width: 60,
				fixed: !0,
				formatter: a,
				align: "center",
				sortable: !1
			}, {
				name: "billDate",
				index: "billDate",
				width: 100,
				align: "center"
			}, {
				name: "billNo",
				index: "billNo",
				width: 200,
				align: "center"
			}, {
				name: "contactName",
				index: "contactName",
				width: 530,
				align: "center"
			}, {
				name: "totalQty",
				index: "totalQty",
				hidden: hiddenAmount,
				width: 100,
				align: "right",
				formatter: "currency"
			}, {
				name: "amount",
				index: "amount",
				hidden: hiddenAmount,
				width: 100,
				align: "right",
				formatter: "currency"
			}, {
				name: "liname",
				index: "liname",
				align: "center",
				width: 80
			}, /*{
				name: "hxStateCode",
				index: "hxStateCode",
				width: 80,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis",
				formatter: function(a) {
					switch (a) {
					case 0:
						return "未" + c + "款";
					case 1:
						return "部分" + c + "款";
					case 2:
						return "全部" + c + "款";
					default:
						return "&#160"
					}
				}
			},*/ {
				name: "userName",
				index: "userName",
				width: 80,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis"
			}, {
				name: "description",
				index: "description",
				width: 200,
				classes: "ui-ellipsis",
				sortable: !1
			}, {
				name: "disEditable",
				label: "不可编辑",
				index: "disEditable",
				hidden: !0
			}],
			cmTemplate: {
				sortable: !0,
				title: !1
			},
			page: 1,
			pager: "#page",
			rowNum: 100,
			rowList: [100, 200, 500],
			viewrecords: !0,
			shrinkToFit: !1,
			forceFit: !1,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				repeatitems: !1,
				id: "id"
			},
			loadComplete: function(a) {
				if (billRequiredCheck) for (var b = a.data.rows, c = 0; c < b.length; c++) {
					var d = b[c];
					d.checked || $("#" + d.id).addClass("gray")
				}
				"150502" == queryConditions.transType && $("#grids").find(".jqgrow").addClass("red")
			},
			loadError: function() {},
			ondblClickRow: function(a) {
				$("#" + a).find(".ui-icon-pencil").trigger("click")
			}
		})
	},
	reloadData: function(a) {
		$("#grids").jqGrid("setGridParam", {
			url: "../scm/invSa/purListmain?action=purListmain",
			datatype: "json",
			postData: a
		}).trigger("reloadGrid")
	},
	addEvent: function() {
		var a = this;
		if ($(".grid-wrap").on("click", ".ui-icon-pencil", function(a) {
			a.preventDefault();
			var b = $(this).parent().data("id"),
				c = $("#grids").jqGrid("getRowData", b),
				d = 1 == c.disEditable ? "&disEditable=true" : "",
				e = ($("#grids").jqGrid("getDataIDs"), "物资出库单"),
				f = "purchase-purchase";
			"150502" == queryConditions.transType ? (e = "购货退货单", f = "purchase-purchaseBack", parent.cacheList.purchaseBackId = $("#grids").jqGrid("getDataIDs")) : parent.cacheList.purchaseId = $("#grids").jqGrid("getDataIDs"), parent.tab.addTabItem({
				tabid: f,
				text: e,
				url: "../scm/invSa?action=editSale&id=" + b + "&flag=list" + d + "&transType=" + queryConditions.transType
			})
		}), $(".grid-wrap").on("click", ".ui-icon-trash", function(a) {
			
			if (a.preventDefault(), Business.verifyRight("PU_DELETE")) {
				var b = $(this).parent().data("id");
				$.dialog.confirm("您确定要删除该购货记录吗？", function() {
					$.dialog.prompt("请再次输入密码！", function(a) {
						Public.ajaxGet("../scm/invPu/showpwd?action=showpwd", {
							userpwd:a,
							username: SYSTEM.userName
							
						}, function(a) {
							if(200 === a.status){
								Public.ajaxGet("../scm/invSa/delete?action=delete", {
									id: b
								}, function(a) {
									200 === a.status ? ($("#grids").jqGrid("delRowData", b), parent.Public.tips({
										content: "删除成功！"
									})) : parent.Public.tips({
										type: 1,
										content: a.msg
									})
								})
							}else{
								parent.Public.tips({
									type: 1,
									content: a.msg
								})
							}
							
						})
						//alert(a);
					})
					//var name=prompt("请输入您的名字","");
					
				})
			}
		}),
		$(".wrapper").on("click", "#btn-batchDel", function(a) {
			if (!Business.verifyRight("PU_DELETE")) return void a.preventDefault();
			var b = $("#grids").jqGrid("getGridParam", "selarrrow"),
				c = b.join();
			if (!c) return void parent.Public.tips({
				type: 2,
				content: "请先选择需要删除的项！"
			});
			var d = "物资出库单";
			"150502" == queryConditions.transType && (d = "购货退货单"), $.dialog.confirm("您确定要删除选中的" + d + "吗？", function() {
				Public.ajaxPost("../scm/invSa/delete?action=delete", {
					id: c
				}, function(a) {
					if (200 === a.status && a.msg && a.msg.length) {
						var b = "<p>操作成功！</p>";
						for (var c in a.msg)"function" != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? "" : "red") + '">' + d + "［" + c.id + "］删除" + (1 == c.isSuccess ? "成功！" : "失败：" + c.msg) + "</p>");
						parent.Public.tips({
							content: b
						})
					} else parent.Public.tips({
						type: 1,
						content: a.msg
					});
					$("#search").trigger("click")
				})
			})
		}), 
			$(".wrapper").on("click", "#print", function(a) {
			a.preventDefault(), Business.verifyRight("PU_PRINT") && Public.print({
				title: "物资出库单列表",
				$grid: $("#grids"),
				pdf: "../scm/invSa/toPdf?action=toPdf",
				billType: 10101,
				filterConditions: queryConditions
			})
		}), $(".wrapper").on("click", "#export", function(a) {
			if (!Business.verifyRight("PU_EXPORT")) return void a.preventDefault();
			var b = $("#grids").jqGrid("getGridParam", "selarrrow"),
				c = b.join(),
				d = c ? "&id=" + c : "";
			for (var e in queryConditions) queryConditions[e] && (d += "&" + e + "=" + queryConditions[e]);
			var f = "../scm/invSa/exportInvPu?action=exportInvPu" + d;
			$(this).attr("href", f)
		}), billRequiredCheck) {
			{
				$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block")
			}
			$(".wrapper").on("click", "#audit", function(a) {
				a.preventDefault();
				var b = $("#grids").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void Public.ajaxPost("../scm/invSa/batchCheckInvPu?action=batchCheckInvPu", {
					id: c
				}, function(a) {
					if (200 === a.status) {
						for (var c = 0, d = b.length; d > c; c++) $("#grids").setCell(b[c], "checkName", system.realName), $("#" + b[c]).removeClass("gray");
						parent.Public.tips({
							content: "审核成功！"
						})
					} else parent.Public.tips({
						type: 1,
						content: a.msg
					})
				}) : void parent.Public.tips({
					type: 2,
					content: "请先选择需要审核的项！"
				})
			}), $(".wrapper").on("click", "#reAudit", function(a) {
				a.preventDefault();
				var b = $("#grids").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void Public.ajaxPost("../scm/invSa/rsBatchCheckInvPu?action=rsBatchCheckInvPu", {
					id: c
				}, function(a) {
					if (200 === a.status) {
						for (var c = 0, d = b.length; d > c; c++) $("#grids").setCell(b[c], "checkName", "&#160;"), $("#" + b[c]).addClass("gray");
						parent.Public.tips({
							content: "反审核成功！"
						})
					} else parent.Public.tips({
						type: 1,
						content: a.msg
					})
				}) : void parent.Public.tips({
					type: 2,
					content: "请先选择需要反审核的项！"
				})
			})
		}
		$("#search").click(function() {
			if(urlParam.Type=="chukudan"){
				queryConditions.matchCon = "请输入单据号或项目名" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.mname = a.$_mname.val(),queryConditions.type = "chukudan", queryConditions.mnumber = a.$_mnumber.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), THISPAGE.reloadData(queryConditions)
		
			}else{
				queryConditions.matchCon = "请输入单据号或项目名" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.mname = a.$_mname.val(),queryConditions.mnumber = a.$_mnumber.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), THISPAGE.reloadData(queryConditions)
		
			}
			queryConditions.matchCon = "请输入单据号或项目名" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.mname = a.$_mname.val(),queryConditions.type = "chukudan", queryConditions.mnumber = a.$_mnumber.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), THISPAGE.reloadData(queryConditions)
		}), $("#add").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("PU_ADD")) {
				var b = "物资出库单",
					c = "purchase-purchase";
				if ("150502" == queryConditions.transType) var b = "购货退货单",
					c = "purchase-purchaseBack";
				parent.tab.addTabItem({
					tabid: c,
					text: b,
					url: "../scm/invSa?action=initPur&transType=" + queryConditions.transType
				})
			}
		}), $(window).resize(function() {
			Public.resizeGrid()
		})
	}
};
THISPAGE.init();