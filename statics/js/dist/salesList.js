var queryConditions = {
	matchCon: ""
},
	SYSTEM = system = parent.SYSTEM,
	hiddenAmount = !1,
	billRequiredCheck = system.billRequiredCheck,
	//billRequiredCheck = 0,
	urlParam = Public.urlParam();
queryConditions.transType = "150502" === urlParam.transType ? "150502" : "150501";
queryConditions.checked = $('#checked').val();
var THISPAGE = {
	init: function() {
		SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_INAMOUNT || (hiddenAmount = !0), this.initDom(), this.loadGrid(), this.addEvent()
	},
	initDom: function() {
		this.$_matchCon = $("#matchCon"),this.$_mname = $("#mname"),this.$_billNo = $("#billNo"),this.$_status = $("select[name='status']"),this.$_mnumber = $("#mnumber"),this.$_ordernumber = $("#ordernumber"),this.$_mdescription = $("#mdescription"), this.$_beginDate = $("#beginDate").val(system.beginDate), this.$_endDate = $("#endDate").val(system.endDate), this.$_matchCon.placeholder(), this.$_beginDate.datepicker(), this.$_endDate.datepicker()
	},
	loadGrid: function() {
		function a(a, b, c) {
			var d;
			if(SYSTEM.userName=="admin"){
				d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a></div>';
				//d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a><a class="ui-icon ui-icon-trash" title="删除"></a></div>';
			}else{
				d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-pencil" title="修改"></a></div>';
			}
			return d
		}
        function addCellAttr(rowId, val, rawObject, cm, rdata) {
            return "style='color:red'";
        }
		// $("#grid").on("click", ".handle-danju-click", function(event) {
		// 	event.stopPropagation();
		// 	var rowId = $(this).data("rowid");
		// 	var rowData = $("#grid").jqGrid('getRowData', rowId);
		// 	var danjuBianhao = rowData.billNo;
		// 	chuku(danjuBianhao);
		// }); 
		$("#grid").on("click", ".handle-danju-click", function(event) {
			event.stopPropagation();
			var rowId = $(this).data("rowid");
			var rowData = $("#grid").jqGrid('getRowData', rowId);
			var danjuBianhao = rowData.billNo;
		
			$.dialog.confirm("<strong>"+ danjuBianhao+"</strong>出库？" , function() {
				chuku(danjuBianhao);
			}, function() {
				// 用户点击取消按钮时执行的操作
				// 默认行为是关闭弹窗，无需添加任何操作
			});
		});
		function chukuButtonFormatter(cellvalue, options, rowObject) {
			return "<button class='ui-label ui-label-success handle-danju-click' data-rowid='" + options.rowId + "'>单据出库</button>";
		}
        function bootSwitch(a, b, c) {
            var d = 1 == a ? "已出库" : "未出库",
				z = 1 == a ? "yichu"  : "set-chuku_status",
                e = 1 == a ? "ui-label-default" : "ui-label-success";
            return '<span class="'+ z +' ui-label ' + e + '" data-chuku_status="' + a + '" data-id="' + c.id + '">' + d + "</span>"
        }
        function bootSwitch_(a, b, c) {
            var d = 1 == a ? "已出库" : "未出库",
                e = 1 == a ? "ui-label-default" : "ui-label-success";
            return '<span class="ui-label ' + e + '" data-chuku_status="' + a + '" data-id="' + c.id + '">' + d + "</span>"
        }
		function d(a, b, c) {
			var d = 1 == a ? "竣工禁用" : "项目启用",
				e = 1 == a ? "ui-label-default" : "ui-label-success";
			return '<span class="set-status ui-label ' + e + '" data-delete="' + a + '" data-id="' + c.id + '">' + d + "</span>"
		}
		function d1(a, b, c) {
			var d = 1 == a ? "计划外" : "已下达",
				e = 1 == a ? "ui-label-default" : "ui-label-success";
			return '<span class="set-design ui-label ' + e + '" data-design="' + a + '" data-id="' + c.id + '">' + d + "</span>"
		}
		function d2(a, b, c) {
			var d = 1 == a ? "已送审" : "未送审",
				e = 1 == a ? "ui-label-default" : "ui-label-success";
			return '<span class="set-apply ui-label ' + e + '" data-apply="' + a + '" data-id="' + c.id + '">' + d + "</span>"
		}
		function d3(a, b, c) {
			var d = 1 == a ? "已核对" : "未核对",
				e = 1 == a ? "ui-label-default" : "ui-label-success";
			return '<span class="set-check ui-label ' + e + '" data-check="' + a + '" data-id="' + c.id + '">' + d + "</span>"
		}
        $("#grid").on("click", ".set-chuku_status", function(a) {
            if (a.stopPropagation(), a.preventDefault(), Business.verifyRight("INVLOCTION_UPDATE")) {
                var b = $(this).data("id"),
                    c = !$(this).data("chuku_status");
                setStatus(b, c)
            }
        })
        function setStatus(a, b) {
            $.dialog.confirm("是否更改出库状态？", function() {
                a && Public.ajaxPost("../basedata/contact/chukuStatus?action=chukuStatus", {
                    contactIds: a,
                    chuku_status: Number(b)
                }, function(c) {
                    c && 200 == c.status ? (parent.Public.tips({
                        content: "出库状态修改成功！"
                    }), $("#grid").jqGrid("setCell", a, "chuku_status", b),window.location.reload()) : parent.Public.tips({
                        type: 1,
                        content: "出库状态修改失败！" + c.msg
                    })
                })
            })
        }
        $("#btn-chuku").click(function(a) {
            a.preventDefault();
            var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
            return b && 0 != b.length ? void setStatus(b, !1) : void parent.Public.tips({
                type: 1,
                content: " 请先选择要更改状态为出库的项目！"
            })
        })
		var b = Public.setGrid();
		queryConditions.beginDate = this.$_beginDate.val(), queryConditions.endDate = this.$_endDate.val();
		var c = "150501" == queryConditions.transType ? "付" : "退";
		if(urlParam.Type=="chukudan"){

		$("#grid").jqGrid({
			url: "../scm/invSa?action=list&type=chukudan",
			postData: queryConditions,
			datatype: "json",
			autowidth: !0,
			height: b.h,
			altRows: !0,
			gridview: !0,
			// multiselect: !0,
			colNames: [/*"操作", */"物料编号","物料描述", "数量","单位","单价", "总金额","采购订单号","单据类型","编号", "项目名"/*, "已" + c + "款"*/, "领料人"/*,"备注"*/, "单据日期", "仓库位置","物资状态" ,"超期时间提示", "出库状态",
				"项目类别",  "是否下达", "是否报审", "是否核对",
				"订单来源"],
			colModel: [/*{
				name: "operating",
				width: 60,
				fixed: !0,
				formatter: a,
				align: "center",
				sortable: !1
			}, */
			{
				name: "goodsnumber",
				index: "goodsnumber",
				width: 80,
				align: "center"
			}, {
				name: "mdescription",
				index: "mdescription",
				width: 250,
				align: "center"
			}, {
				name: "totalQty",
				index: "totalQty",
				width: 80,
				align: "center"
			}, {
				name: "mainUnit",
				index: "mainUnit",
				width: 50,
				align: "center"
			},
			 {
				name: "price",
				index: "price",
				width: 60,
				align: "center"
			}, {
				name: "amount",
				index: "amount",
				hidden: hiddenAmount,
				width: 60,
				align: "right"
			}, {
				name: "ordernumber",
				index: "ordernumber",
				width: 100,
				align: "center"
			}, {
				name: "BillName",
				index: "BillName",
				width: 80,
				align: "center"
			}, {
				name: "billNo",
				index: "billNo",
				width: 80,
				align: "center"
			}, {
				name: "contactName",
				index: "contactName",
				width: 400,
				align: "center"
			}, {
				name: "liname",
				index: "liname",
				align: "center",
				width: 80
			}, {
                name: "billDate",
                index: "billDate",
                align: "center",
                width: 80
            }, {
                name: "locationNames",
                index: "locationNames",
                align: "center",
                width: 120
            }, 
			{
                name: "chuku_status",
                index: "chuku_status",
                width: 110,
                align: "center",
                formatter: bootSwitch_,
                classes: "ui-ellipsis",
                sortable: !1
            },
			{
                name: "is_chaoqi",
                index: "is_chaoqi",
                align: "center",
                width: 80
            }, 
				// 新增字段
				{
					name: "customerType",
					label: "项目类别",
					index: "customerType",
					width: 100,
					fixed: !0,
					title: !1
				},
				{
					name: "delete",
					label: "状态",
					index: "delete",
					width: 80,
					align: "center",
					formatter: d
				},
				{
					name: "design",
					label: "是否下达",
					index: "design",
					width: 80,
					align: "center",
					formatter: d1
				},
				{
					name: "apply",
					label: "是否送审",
					index: "apply",
					width: 80,
					align: "center",
					formatter: d2
				},
				{
					name: "check",
					label: "是否核对",
					index: "check",
					width: 80,
					align: "center",
					formatter: d3
				},
				/*{
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
			}, {
				name: "description",
				index: "description",
				width: 120,
				classes: "ui-ellipsis",
				sortable: !1
			},*/ {
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
				"150502" == queryConditions.transType && $("#grid").find(".jqgrow").addClass("red")
			},
			gridComplete: function() {
                var oldnumber=0;
                var number=0;
                $("#grid").find("tr").each(function(){
                    var tdArr = $(this).children();
                    var history_income_type = Number(tdArr.eq(3).text());
                    //var history_income_money = Number(tdArr.eq(9).text());
                    var history_income_remark = Number(tdArr.eq(6).text());
                    oldnumber+=history_income_type;
                    //newnumber+=history_income_money;
                    number+=history_income_remark;



                });

                $("#grid tbody").append(
                    "<tr role='row' style='height:40px;'>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
                    "<td role='gridcell'  style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'>合计：</td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'></td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'>"+parseFloat(oldnumber.toFixed(3))+"</td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'></td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'></td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'>"+parseFloat(number.toFixed(3))+"</td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'></td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
                    "<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
                    "</tr>"
                );
            },
			loadError: function() {},
			ondblClickRow: function(a) {
				$("#" + a).find(".ui-icon-pencil").trigger("click")
			}
		})
		}else{

		$("#grid").jqGrid({
			url: "../scm/invSa?action=list",
			postData: queryConditions,
			datatype: "json",
			autowidth: !0,
			height: b.h,
			altRows: !0,
			gridview: !0,
			multiselect: !0,
			colNames: ["操作", "单据日期", "单据编号", "项目名"/*, "总数量", "总金额", "已" + c + "款"*/, "领料人", "制单人", "审核人", "仓库","备注","超期时间提示","出库状态确定", "单据出库","单据出库1"],
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
				width: 100,
				align: "center"
			}, {
				name: "contactName",
				index: "contactName",
				width: 500,
				align: "center"
			}, /*{
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
			},*/ {
				name: "liname",
				index: "liname",
				align: "center",
				width: 100
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
				name: "checkName",
				index: "checkName",
				width: 80,
				hidden: billRequiredCheck ? !1 : !0,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis"
			}, {
                name: "locationNames",
                index: "locationNames",
                width: 200,
                align: "center",
                classes: "ui-ellipsis",
                sortable: !1
            },{
				name: "description",
				index: "description",
				width: 80,
                align: "center",
				classes: "ui-ellipsis",
				sortable: !1
			}, {
                name: "is_chaoqi",
                index: "is_chaoqi",
                width: 110,
                align: "center",
                classes: "ui-ellipsis",
                cellattr: addCellAttr,
                sortable: !1
            }, {
                name: "chuku_status",
                index: "chuku_status",
                width: 110,
                align: "center",
                formatter: bootSwitch,
                classes: "ui-ellipsis",
                sortable: !1
            }, {
				name: "chukuBtn",
				index: "chukuBtn",
				width: 100,
				align: "center",
				sortable: !1,
				formatter: chukuButtonFormatter // 使用自定义的渲染函数
			},{
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
				"150502" == queryConditions.transType && $("#grid").find(".jqgrow").addClass("red")
			},
			loadError: function() {},
			ondblClickRow: function(a) {
				$("#" + a).find(".ui-icon-pencil").trigger("click")
			}
		})
		}
	},
	reloadData: function(a) {
		$("#grid").jqGrid("setGridParam", {
			url: "../scm/invSa?action=list",
			datatype: "json",
			postData: a
		}).trigger("reloadGrid")
	},
	addEvent: function() {
		var a = this;
		if ($(".grid-wrap").on("click", ".ui-icon-pencil", function(a) {
			a.preventDefault();
			var b = $(this).parent().data("id"),
				c = $("#grid").jqGrid("getRowData", b),
				d = 1 == c.disEditable ? "&disEditable=true" : "",
				e = ($("#grid").jqGrid("getDataIDs"), "物资出库单"),
				f = "purchase-purchase";
			"150502" == queryConditions.transType ? (e = "购货退货单", f = "purchase-purchaseBack", parent.cacheList.purchaseBackId = $("#grid").jqGrid("getDataIDs")) : parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs"), parent.tab.addTabItem({
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
									200 === a.status ? ($("#grid").jqGrid("delRowData", b), parent.Public.tips({
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
			var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
				c = b.join();
			if (!c) return void parent.Public.tips({
				type: 2,
				content: "请先选择需要删除的项！"
			});
			var d = "物资出库单";
			"150502" == queryConditions.transType && (d = "购货退货单"), $.dialog.confirm("您确定要删除选中的" + d + "吗？", function() {

				$.dialog.prompt("请再次输入密码！", function(a) {
						Public.ajaxGet("../scm/invPu/showpwd?action=showpwd", {
							userpwd:a,
							username: SYSTEM.userName

						}, function(a) {
							if(200 === a.status){

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
							}else{
								parent.Public.tips({
									type: 1,
									content: a.msg
								})
							}

						})
						//alert(a);
					})

			})
		}),
			$(".wrapper").on("click", "#print", function(a) {
			a.preventDefault(), Business.verifyRight("PU_PRINT") && Public.print({
				title: "物资出库单列表",
				$grid: $("#grid"),
				pdf: "../scm/invSa/toPdf?action=toPdf",
				billType: 10101,
				filterConditions: queryConditions
			})
		}), $(".wrapper").on("click", "#export", function(a) {
			if (!Business.verifyRight("PU_EXPORT")) return void a.preventDefault();
			var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
				c = b.join(),
				d = c ? "&id=" + c : "";
			for (var e in queryConditions) queryConditions[e] && (d += "&" + e + "=" + queryConditions[e]);

			if(urlParam.Type=="chukudan"){
				if(urlParam.action=="initSaleListCancel"){
                    var f = "../scm/invSa/exportInvPuZuofei?action=exportInvPuZuofei" + d;
                }else{
                    var f = "../scm/invSa/exportInvPu?action=exportInvPu" + d;
                }
			}else{
				var f = "../scm/invSa/exportInvPus?action=exportInvPus" + d;
			}

			$(this).attr("href", f)
		}), billRequiredCheck) {
			{
				$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block")
			}
			$(".wrapper").on("click", "#audit", function(a) {
				a.preventDefault();
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void Public.ajaxPost("../scm/invSa/batchCheckInvPu?action=batchCheckInvPu", {
					id: c
				}, function(a) {
					if (200 === a.status) {
						for (var c = 0, d = b.length; d > c; c++) $("#grid").setCell(b[c], "checkName", system.realName), $("#" + b[c]).removeClass("gray");
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
				var b = $("#grid").jqGrid("getGridParam", "selarrrow"),
					c = b.join();
				return c ? void Public.ajaxPost("../scm/invSa/rsBatchCheckInvPu?action=rsBatchCheckInvPu", {
					id: c
				}, function(a) {
					if (200 === a.status) {
						for (var c = 0, d = b.length; d > c; c++) $("#grid").setCell(b[c], "checkName", "&#160;"), $("#" + b[c]).addClass("gray");
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

				queryConditions.matchCon = "请输入单据号或项目名" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.mname = a.$_mname.val(),queryConditions.billNo = a.$_billNo.val(),queryConditions.type = "chukudan", queryConditions.mnumber = a.$_mnumber.val(),queryConditions.ordernumber = a.$_ordernumber.val(),queryConditions.mdescription = a.$_mdescription.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), THISPAGE.reloadData(queryConditions)

			}else{
				queryConditions.matchCon = "请输入单据号或项目名" === a.$_matchCon.val() ? "" : a.$_matchCon.val(), queryConditions.mname = a.$_mname.val(),queryConditions.billNo = a.$_billNo.val(),queryConditions.status = a.$_status.val(),queryConditions.mnumber = a.$_mnumber.val(),queryConditions.ordernumber = a.$_ordernumber.val(),queryConditions.mdescription = a.$_mdescription.val(), queryConditions.beginDate = a.$_beginDate.val(), queryConditions.endDate = a.$_endDate.val(), THISPAGE.reloadData(queryConditions)

			}
			}), $("#add").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("PU_ADD")) {
				var b = "物资领料单",
					c = "sales-sales";
				if ("150502" == queryConditions.transType) var b = "购货退货单",
					c = "sales-salesBack";
				parent.tab.addTabItem({
					tabid: c,
					text: b,
					url: "../scm/invSa?action=initSale&transType=150602"
				})
			}
		}), $(window).resize(function() {
			Public.resizeGrid()
		})
	}
};
THISPAGE.init();
