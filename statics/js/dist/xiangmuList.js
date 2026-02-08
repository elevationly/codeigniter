$(function() {
	var SYSTEM = system = parent.SYSTEM;
	var oldnumber=0;
	var newnumber=0;
	var number=0;
	var flag=1;
	function a() {
		var b = $(this),
				c = b.html();
			(b.html("&lt;&lt;"), g = 0, $("#tree").hide(), Public.resizeGrid(f, g))
		Public.zTree.init($("#tree"), {
			defaultClass: "innerTree",
			showRoot: !0,
			rootTxt: "全部"
		}, {
			callback: {
				beforeClick: function(a, b) {
					$("#currentCategory").data("id", b.id).html(b.name), $("#search").trigger("click")
				}
			}
		})
	}
	function b() {
		var a = Public.setGrid(f, g),
			b = parent.SYSTEM.rights,
			c = !(parent.SYSTEM.isAdmin || b.AMOUNT_COSTAMOUNT),
			h = !(parent.SYSTEM.isAdmin || b.AMOUNT_INAMOUNT),
			k = !(parent.SYSTEM.isAdmin || b.AMOUNT_OUTAMOUNT),
			l = [{
				name: "operate",
				label: "操作",
				width: 60,
				fixed: !0,
				formatter: function(a, b, c) {	
					if(SYSTEM.userName=="admin"){
						var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
					
					}else{
						var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
					
					}
					return d
				},
				title: !1
			}, {
				name: "id",
				label: "商品id",
				index: "id",
				width: 100,
				title: !1,
				hidden:!0
			}, {
				name: "names",
				label: "项目名称",
				index: "names",
				width: 200,
				title: !1
			}, {
				name: "ordernumbers",
				label: "项目定义号",
				index: "ordernumbers",
				width: 150,
				classes: "ui-ellipsis"
			}, {
				name: "numbers",
				label: "物料编号",
				index: "numbers",
				align: "center",
				width: 100,
				classes: "ui-ellipsis"
			}, {
				name: "mdescription",
				label: "物料描述",
				index: "mdescription",
				align: "center",
				width: 200,
				classes: "ui-ellipsis"
			}, {
				name: "num",
				label: "出库数量",
				index: "num",
				align: "center",
				width: 100,
				classes: "ui-ellipsis"
			}, {
				name: "mainUnit",
				label: "单位",
				index: "mainUnit",
				width: 100,
				align: "center",
				title: !1
			}, {
				name: "price",
				label: "出库单价",
				index: "price",
				width: 150,
				align: "center",
				title: !1
			},  {
				name: "amount",
				label: "出库金额",
				index: "amount",
				width: 100,
				align: "center",
				title: !1
			},{
				name: "duiwu",
				label: "施工队伍",
				index: "duiwu",
				width: 100,
				align: "center",
				title: !1
			},{
				name: "beizhu",
				label: "备注",
				index: "beizhu",
				width: 80,
				align: "center",
				title: !1
			}/*,{
				name: "Arrivaltime",
				label: "申请时间",
				index: "Arrivaltime",
				width: 80,
				align: "center",
				title: !1
			},{
				name: "flagNo",
				label: "是否到货",
				index: "flagNo",
				width: 80,
				align: "center",
				title: !1
			},{
				name: "flagtime",
				label: "到货时间",
				index: "flagtime",
				width: 80,
				align: "center",
				title: !1
			},{
				name: "flagcontact",
				label: "供应商",
				index: "flagcontact",
				width: 200,
				align: "center",
				title: !1
			}, {
				name: "quantity",
				label: "期初数量",
				index: "quantity",
				width: 80,
				align: "right",
				title: !1,
				formatter: i.quantity
			}, {
				name: "unitCost",
				label: "单位成本",
				index: "unitCost",
				width: 100,
				align: "right",
				formatter: "currency",
				formatoptions: {
					showZero: !0,
					decimalPlaces: d
				},
				title: !1,
				hidden: c
			}, {
				name: "amount",
				label: "期初总价",
				index: "amount",
				width: 100,
				align: "right",
				formatter: "currency",
				formatoptions: {
					showZero: !0,
					decimalPlaces: e
				},
				title: !1,
				hidden: c
			}, {
				name: "purPrice",
				label: "预计采购价",
				index: "purPrice",
				width: 100,
				align: "right",
				formatter: "currency",
				formatoptions: {
					showZero: !0,
					decimalPlaces: d
				},
				title: !1,
				hidden: h
			}, {
				name: "salePrice",
				label: "零售价",
				index: "salePrice",
				width: 100,
				align: "right",
				formatter: "currency",
				formatoptions: {
					showZero: !0,
					decimalPlaces: d
				},
				title: !1,
				hidden: k
			}, {
				name: "remark",
				label: "备注",
				index: "remark",
				width: 100,
				title: !0
			}, {
				name: "delete",
				label: "状态",
				index: "delete",
				width: 80,
				align: "center",
				formatter: i.statusFmatter
			}*/];
		j.gridReg("grid", l), l = j.conf.grids.grid.colModel, $("#grid").jqGrid({
			url: "../basedata/inventory/xiangmulist?action=xiangmulist&isDelete=2",
			datatype: "json",
			width: a.w,
			height: a.h,
			altRows: !0,
			gridview: !0,
			onselectrow: !1,
			colModel: l,
			pager: "#page",
			viewrecords: !0,
			multiselect: !0,
			cmTemplate: {
				sortable: !1
			},
			rowNum: 100,
			rowList: [100, 200, 500],
			shrinkToFit: !1,
			forceFit: !0,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				repeatitems: !1,
				id: "id"
			},
			loadComplete: function(a) {
				if (a && 200 == a.status) {
					var b = {};
					a = a.data;
					for (var c = 0; c < a.rows.length; c++) {
						var d = a.rows[c];
						b[d.id] = d
					}
					$("#grid").data("gridData", b)
				}
			},
			loadError: function(a, b, c) {
				parent.Public.tips({
					type: 1,
					content: "操作失败了哦，请检查您的网络链接！"
				})
			},
			gridComplete: function() {
				if(flag!=0){
					oldnumber=0;
					newnumber=0;
					number=0;
				$("#grid").find("tr").each(function(){
					var tdArr = $(this).children();
					var history_income_type = Number(tdArr.eq(7).text());
					
					//var history_income_money = Number(tdArr.eq(9).text());
					var history_income_remark = Number(tdArr.eq(10).text());
					
					oldnumber+=history_income_type;
					//newnumber+=history_income_money;
					number+=history_income_remark;
					
     
     
				});
				
				$("#grid tbody").append(
					"<tr role='row' style='height:40px;'>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
					"<td role='gridcell'  style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'>合计：</td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'>"+parseFloat(oldnumber.toFixed(3))+"</td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;text-align:center;'>"+parseFloat(number.toFixed(3))+"</td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
					"<td role='gridcell' style='border-right:1px solid #D6DEE3;border-bottom:1px solid #D6DEE3;'></td>" +
					"</tr>"
				);
				}
			},
			resizeStop: function(a, b) {
				j.setGridWidthByIndex(a, b, "grid")
			}
		}).navGrid("#page", {
			edit: !1,
			add: !1,
			del: !1,
			search: !1,
			refresh: !1
		}).navButtonAdd("#page", {
			caption: "",
			buttonicon: "ui-icon-config",
			onClickButton: function() {
				j.config()
			},
			position: "last"
		})
	}
	function c() {
		$_matchCon = $("#matchCon"),$_mdescription = $("#mdescription"),$_ordernumber = $("#ordernumber"),$_number = $("#number"), $_matchCon.placeholder(), $("#search").on("click", function(a) {
			a.preventDefault();
			
			var b = "按商品编号，商品名称，规格型号等查询" === $_matchCon.val() ? "" : $.trim($_matchCon.val()),
				ordernumber=$.trim($_ordernumber.val()),
				mdescription=$.trim($_mdescription.val()),
				number=$.trim($_number.val()),
				
				c = $("#currentCategory").data("id");
				if(b!="" || mdescription!="" || ordernumber!="" || number!=""){
					flag=1;
				}else{
					
					flag=0;
				}
			$("#grid").jqGrid("setGridParam", {
				page: 1,
				postData: {
					skey: b,
					ordernumber:ordernumber,
					number:number,
					mdescription:mdescription,
					assistId: c
				}
			}).trigger("reloadGrid")
		}), $("#btn-add").on("click", function(a) {
			var b, c = this;
				c.import_dialog = $.dialog({
					width: 740,
					height: 330,
					title: "新增库存",
					content: "url:../settings/customer_stock",
					data: {
						curID: a.curID,
						callback: function(b) {
							var d = "上传失败！";
							if (b && b.msg) {
								if ("success" === b.msg) return c.loading.close(), c.import_dialog.close(), void a.manager.makeOrder(b.data);
								d = b.msg
							}
							parent.Public.tips({
								type: 1,
								content: d
							}), c.loading.close()
						}
					},
					lock: !0,
					cancel: !0
				})
		}), $("#btn-print").on("click", function(a) {
			a.preventDefault()
		}), $("#btn-import").on("click", function(a) {
			a.preventDefault(), Business.verifyRight("BaseData_IMPORT") && parent.$.dialog({
				width: 560,
				height: 300,
				title: "批量导入",
				content: "url:../import",
				lock: !0
			})
		}), $("#btn-export").on("click", function(a) {
		
			if (Business.verifyRight("IO_daochu")) {
				var b = "按商品编号，商品名称，规格型号等查询" === $_matchCon.val() ? "" : $.trim($_matchCon.val()),
					c = $("#currentCategory").data("id") || "";
					ordernumber=$_ordernumber.val();
					mdescription=$_mdescription.val();
					numbers=$_number.val();
					
					$(this).attr("href", "../basedata/inventory/exporterxiangmu?action=exporterxiangmu&fg=true&isDelete=2&skey=" + b + "&assistId=" + c+"&ordernumber="+ordernumber+"&oldnumber="+oldnumber+"&mdescription="+mdescription+"&number="+number+"&numbers="+numbers)				
					
				
			}
		}), $("#grid").on("click", ".operating .ui-icon-pencil", function(a) {	
		if (a.preventDefault(), Business.verifyRight("IO_xiugai")) {
			$(".update_box").show();
			$(".Covering").show();
			var ordersid = $(this).parent().data("id"); 
			
			Public.ajaxPost("../basedata/inventory/updatexiangmu?action=updatexiangmu", {
						id:ordersid
					}, function(b) {												
						if (b && 200 == b.status) {
							$(".h_us").val(b.id);
							$(".name").val(b.name);
							$(".ordernumbers").val(b.ordernumber);
							$(".numbers").val(b.number);
							$(".mdescriptions").val(b.mdescription);
							$(".num").val(b.num);
							$(".mainUnit").val(b.mainUnit);
							$(".price").val(b.price);
							$(".amount").val(b.amount);
							$(".duiwu").val(b.duiwu);
							$(".beizhu").val(b.beizhu);
							
							
						} else parent.Public.tips({
							type: 1,
							content: "修改商品失败！" + b.msg
						})
			})
		}
		}), $(".ordersbtn").click(function(){
			var id = $(".h_us").val();
			var name = $(".name").val();
			var ordernumber = $(".ordernumbers").val();
			var number = $(".numbers").val();
			var mdescription = $(".mdescriptions").val();
			var num = $(".num").val();
			var mainUnit = $(".mainUnit").val();
			var price = $(".price").val();
			var amount = $(".amount").val();
			var duiwu = $(".duiwu").val();
			var beizhu = $(".beizhu").val();
		
			$.dialog.prompt("请再次输入密码！", function(a) {
						Public.ajaxGet("../scm/invPu/showpwd?action=showpwd", {
							userpwd:a,
							username: SYSTEM.userName
							
						}, function(a) {
							if(200 === a.status){
								
								Public.ajaxPost("../basedata/inventory/updatexiangmus?action=updatexiangmus", {
						id:id,
						name:name,
						ordernumber:ordernumber,
						number:number,
						mdescription:mdescription,
						num:num,
						mainUnit:mainUnit,
						price:price,
						amount:amount,
						duiwu:duiwu,
						beizhu:beizhu
					}, function(b) {												
						if (b && 200 == b.status) {
							
							parent.Public.tips({
								type: 3,
								content: "修改成功！"
							})
							
							$(".update_box").hide();
							$(".Covering").hide();
							window.location.reload();
							
						} else parent.Public.tips({
							type: 1,
							content: "修改商品失败！"
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
			
		}), $("#grid").on("click", ".operating .ui-icon-trash", function(a) {
			if (a.preventDefault(), Business.verifyRight("IO_shanchu")) {
				var b = $(this).parent().data("id");
				$.dialog.confirm("您确定要删除该记录吗？", function() {
					$.dialog.prompt("请再次输入密码！", function(a) {
						Public.ajaxGet("../scm/invPu/showpwd?action=showpwd", {
							userpwd:a,
							username: SYSTEM.userName
							
						}, function(a) {
							if(200 === a.status){
								Public.ajaxGet("../scm/invPu/deletexiangmu?action=deletexiangmu", {
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
		}), $("#grid").on("click", ".operating .ui-icon-pic", function(a) {
			a.preventDefault();
			var b = $(this).parent().data("id"),
				c = "商品图片";
			$.dialog({
				content: "url:../settings/fileUpload",
				data: {
					title: c,
					id: b,
					callback: function() {}
				},
				title: c,
				width: 775,
				height: 470,
				max: !1,
				min: !1,
				cache: !1,
				lock: !0
			})
		}), $("#btn-batchDel").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("IO_shanchu")) {
				var b = $("#grid").jqGrid("getGridParam", "selarrrow");
				var bs=b.join();
				bsc=bs.charAt(bs.length-1,1);
				if(bsc==","){
					bs=bs.substring(0,bs.length-1);				
				}
				b.length ? h.del(bs) : parent.Public.tips({
					type: 2,
					content: "请选择需要删除的项"
				})
			}
		}), $("#daohuo").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("INVENTORY_DELETE")) {
				var b = $("#grid").jqGrid("getGridParam", "selarrrow");
				b.length ? h.daohuo(b.join()) : parent.Public.tips({
					type: 2,
					content: "请选择到货的项"
				})
			}
		}), $(".wrapper").on("click", "#daoru", function(a) {
				if (a.preventDefault(), Business.verifyRight("IO_daoru")) {
					var c = $(this);
					$.dialog({
						content: "url:../settings/xiangmu",
						data: {
							title: 'excel上传',
							id: "<?php echo $billNo?>",
							callback: function() {
								
							}
						},
						title: 'excel上传',
						width: 300,
						height: 130,
						max: !1,
						min: !1,
						cache: !1,
						lock: !0,
						close: function() {window.location.reload();
						}
					})
				}
				
			}), $("#btn-disable").click(function(a) {
			a.preventDefault();
			var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
			return b && 0 != b.length ? void h.setStatuses(b, !0) : void parent.Public.tips({
				type: 1,
				content: " 请先选择要禁用的商品！"
			})
		}), $("#btn-enable").click(function(a) {
			a.preventDefault();
			var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
			return b && 0 != b.length ? void h.setStatuses(b, !1) : void parent.Public.tips({
				type: 1,
				content: " 请先选择要启用的商品！"
			})
		}), $("#hideTree").click(function(a) {
			a.preventDefault();
			var b = $(this),
				c = b.html();
			(b.html("&lt;&lt;"), g = 0, $("#tree").hide(), Public.resizeGrid(f, g))
		}), $("#grid").on("click", ".set-status", function(a) {
			if (a.stopPropagation(), a.preventDefault(), Business.verifyRight("INVLOCTION_UPDATE")) {
				var b = $(this).data("id"),
					c = !$(this).data("delete");
				h.setStatus(b, c)
			}
		}), $(window).resize(function() {
			Public.resizeGrid(f, g), $(".innerTree").height($("#tree").height() - 95)
		}), Public.setAutoHeight($("#tree")), $(".innerTree").height($("#tree").height() - 95)
	}
	var d = (parent.SYSTEM, Number(parent.SYSTEM.qtyPlaces), Number(parent.SYSTEM.pricePlaces)),
		e = Number(parent.SYSTEM.amountPlaces),
		f = 95,
		g = 270,
		h = {
			operate: function(a, b) {
				if ("add" == a) var c = "新增商品",
					d = {
						oper: a,
						callback: this.callback
					};
				else var c = "修改商品",
					d = {
						oper: a,
						rowId: b,
						callback: this.callback
					};
				var e = 768;
				_h = 480, $.dialog({
					title: c,
					content: "url:goods_manage",
					data: d,
					width: e,
					height: 430,
					max: !1,
					min: !1,
					cache: !1,
					lock: !0
				})
			},
			del: function(a) {
				$.dialog.confirm("删除的将不能恢复，请确认是否删除？", function() {
					$.dialog.prompt("请再次输入密码！", function(b) {
						Public.ajaxGet("../scm/invPu/showpwd?action=showpwd", {
							userpwd:b,
							username: SYSTEM.userName
							
						}, function(b) {
							if(200 === b.status){
					
								Public.ajaxPost("../basedata/inventory/deletexiangmu?action=deletexiangmu", {
									id: a
								}, function(b) {
									if (b && 200 == b.status) {
										var c = b.data.id || [];
										a.split(",").length === c.length ? parent.Public.tips({
											content: "成功删除" + c.length + "个商品！"
										}) : parent.Public.tips({
											type: 2,
											content: b.data.msg
										});
										for (var d = 0, e = c.length; e > d; d++) $("#grid").jqGrid("setSelection", c[d]), $("#grid").jqGrid("delRowData", c[d])
									} else parent.Public.tips({
										type: 1,
										content: "删除物料失败！" + b.msg
									})
									window.location.reload();
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
			},
			daohuo: function(a) {
				$.dialog.prompt("请输入到货仓库！", function(dao) {
					
					$.dialog.confirm("请确认是否到货？", function() {
						Public.ajaxPost("../basedata/inventory/ordersdaohuo?action=ordersdaohuo", {
							id: a,
							dao:dao
						}, function(b) {
							if (b && 200 == b.status) {
								var c = b.data.id || [];
								a.split(",").length === c.length ? parent.Public.tips({
									content: "确认到货" + c.length + "个物资！"
								}) : parent.Public.tips({
									type: 2,
									content: b.data.msg
								});
								for (var d = 0, e = c.length; e > d; d++) $("#grid").jqGrid("setSelection", c[d]), $("#grid").jqGrid("delRowData", c[d])
							} else parent.Public.tips({
								type: 1,
								content: "操作失败！" + b.msg
							})
							window.location.reload();
						})
					})
					
				})
				
			},
			setStatus: function(a, b) {
				a && Public.ajaxPost("../basedata/inventory/disable?action=disable", {
					invIds: a,
					disable: Number(b)
				}, function(c) {
					c && 200 == c.status ? (parent.Public.tips({
						content: "商品状态修改成功！"
					}), $("#grid").jqGrid("setCell", a, "delete", b)) : parent.Public.tips({
						type: 1,
						content: "商品状态修改失败！" + c.msg
					})
				})
			},
			setStatuses: function(a, b) {
				if (a && 0 != a.length) {
					var c = $("#grid").jqGrid("getGridParam", "selarrrow"),
						d = c.join();
					Public.ajaxPost("../basedata/inventory/disable?action=disable", {
						invIds: d,
						disable: Number(b)
					}, function(c) {
						if (c && 200 == c.status) {
							parent.Public.tips({
								content: "商品状态修改成功！"
							});
							for (var d = 0; d < a.length; d++) {
								var e = a[d];
								$("#grid").jqGrid("setCell", e, "delete", b)
							}
						} else parent.Public.tips({
							type: 1,
							content: "商品状态修改失败！" + c.msg
						})
					})
				}
			},
			callback: function(a, b, c) {
				var d = $("#grid").data("gridData");
				d || (d = {}, $("#grid").data("gridData", d)), d[a.id] = a, "edit" == b ? ($("#grid").jqGrid("setRowData", a.id, a), c && c.api.close()) : ($("#grid").jqGrid("addRowData", a.id, a, "last"), c && c.resetForm(a))
			}
		},
		i = {
			money: function(a, b, c) {
				var a = Public.numToCurrency(a);
				return a || "&#160;"
			},
			currentQty: function(a, b, c) {
				if ("none" == a) return "&#160;";
				var a = Public.numToCurrency(a);
				return a
			},
			quantity: function(a, b, c) {
				var a = Public.numToCurrency(a);
				return a || "&#160;"
			},
			statusFmatter: function(a, b, c) {
				var d = a === !0 ? "已禁用" : "已启用",
					e = a === !0 ? "ui-label-default" : "ui-label-success";
				return '<span class="set-status ui-label ' + e + '" data-delete="' + a + '" data-id="' + c.id + '">' + d + "</span>"
			}
		},
		j = Public.mod_PageConfig.init("goodsList");
	b(), a(), c()
});