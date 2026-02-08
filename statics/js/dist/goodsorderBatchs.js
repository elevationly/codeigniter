(function() {
	var u = "basedata/inventory/ordergoods";
	$.ajaxPrefilter(function(o, oo, jqXHR) {
		if (o.url && o.url.indexOf(u) !== -1) {
			o.type = "POST";
			if (o.url.indexOf("?") !== -1) {
				var parts = o.url.split("?"), q = parts[1] || "";
				o.url = parts[0];
				if (q && typeof o.data !== "string") {
					var d = o.data || {};
					$.each(q.split("&"), function(i, pair) {
						var kv = pair.split("=");
						if (kv[0]) d[decodeURIComponent(kv[0])] = decodeURIComponent((kv[1] || "").replace(/\+/g, " "));
					});
					o.data = d;
				}
			}
			if (typeof o.data === "object" && o.data !== null) {
				o.data.locationName = ($("#locationNameCon").val() || "").trim();
				o.data.skey = ($("#matchCon").val() || "").trim();
			}
		}
	});
})();
function callbackSp() {
	var a = parent.THISPAGE || api.data.page,
		b = a.curID,
		c = (a.newId, "fix1"),
		d = (api.data.callback, $("#grid").jqGrid("getGridParam", "selarrrow")),
		e = d.length,
		f = oldRow = parent.curRow,
		g = parent.curCol;
	if (e > 0) {
		parent.$("#fixedGrid").jqGrid("restoreCell", f, g);
		var h = Public.getDefaultPage(),
			i = $("#grid").jqGrid("getRowData", d[0]);
		if (i.id = i.id.split("_")[0], h.SYSTEM.goodsInfo.push(i), "" === i.spec) var j = i.number + " " + i.name;
		else var j = i.number + " " + i.name + "_" + i.spec;
		//else var j = i.number;
		var k = $.extend(!0, {}, i);
		if (k.goods = j, k.id = c, b) var l = parent.$("#fixedGrid").jqGrid("setRowData", b, {});
		l && parent.$("#" + b).data("goodsInfo", i).data("storageInfo", {
			id: i.locationId,
			name: i.locationName
		}).data("unitInfo", {
			unitId: i.unitId,
			name: i.unitName
		}), parent.$("#fixedGrid").jqGrid("setRowData", c, k)
	}
	return d
}
function callback() {
	
	var a = parent.THISPAGE || api.data.page,
		b = a.curID,
		c = a.newId,
		d = api.data.callback,
		e = $("#grid").jqGrid("getGridParam", "selarrrow"),
		f = e.length,
		g = oldRow = parent.curRow,
		h = parent.curCol;
	 
	if (isSingle) {
		parent.$("#grid").jqGrid("restoreCell", g, h);
		var i = $("#grid").jqGrid("getRowData", $("#grid").jqGrid("getGridParam", "selrow"));
		if (i.id = i.id.split("_")[0], delete i.amount, defaultPage.SYSTEM.goodsInfo.push(i), "" === i.spec) var j = i.number + " " + i.name;
	    //else var j = i.number + " " + i.name + "_" + i.spec;
		else var j = i.number;
		if (g > 8 && g > oldRow) var k = g;
		else var k = b;
		var l = parent.$("#grid").jqGrid("getRowData", Number(b));
		l = $.extend({}, l, {
			id: i.id,
			goods: j,
			invNumber: i.number,
			invName: i.name,
			unitName: i.unitName,
			qty: i.inventoryNew,
			price: i.salePrice,
			spec: i.spec,
			skuId: i.skuId,
			skuName: i.skuName,
			isSerNum: i.isSerNum
		});
		var m = $.extend(!0, {}, l);
		parent.$("#" + k).data("goodsInfo", m).data("storageInfo", {
			id: i.locationId,
			name: i.locationName
		}).data("unitInfo", {
			unitId: i.unitId,
			name: i.unitName
		}), d(k, l)
	} else if (f > 0) {
		parent.$("#grid").jqGrid("restoreCell", g, h);
		for (rowid in addList) {
			var i = addList[rowid];
			if (i.id = i.id.split("_")[0], delete i.amount, defaultPage.SYSTEM.goodsInfo.push(i), "" === i.spec) var j = i.number + " " + i.name;
			//else var j = i.number + " " + i.name + "_" + i.spec;
			else var j = i.number;		 
			if (b) var k = b;
			else var k = c;
			var n = $.extend(!0, {}, i);
			if (n.goodsnumber = j, n.id = k, n.qty = n.inventoryNew || 1, b) var o = parent.$("#grid").jqGrid("setRowData", Number(b), {});
			else {
				var o = parent.$("#grid").jqGrid("addRowData", Number(c), {}, "last");
				c++
			}
			o && parent.$("#" + k).data("goodsInfo", i).data("storageInfo", {
				id: i.locationId,
				name: i.locationName
			}).data("unitInfo", {
				unitId: i.unitId,
				name: i.unitName
			}), parent.$("#grid").jqGrid("setRowData", k, n), g++;
			
			var p = parent.$("#" + b).next();
			b = p.length > 0 ? parent.$("#" + b).next().attr("id") : ""
		}
		d(c, b, g), $("#grid").jqGrid("resetSelection"), addList = {}
	}
	return e
}
var queryConditions = {
	action: "ordergoods",
	skey: (frameElement.api.data ? frameElement.api.data.skey : "") || "",
	locationName: ""
},
	$grid = $("#grid"),
	addList = {},
	urlParam = Public.urlParam(),
	zTree, defaultPage = Public.getDefaultPage(),
	SYSTEM = defaultPage.SYSTEM,
	taxRequiredCheck = SYSTEM.taxRequiredCheck;
taxRequiredInput = SYSTEM.taxRequiredInput;
var api = frameElement.api,
	data = api.data || {},
	isSingle = data.isSingle || 0,
	skuMult = data.skuMult,
	THISPAGE = {
		init: function() {
			this.initDom(), this.loadGrid(), this.initZtree(), this.addEvent()
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon").val(queryConditions.skey), this.$_matchCon.placeholder();
			this.$_locationNameCon = $("#locationNameCon").val(queryConditions.locationName || "");
		},
		initZtree: function() {
			zTree = Public.zTree.init($(".grid-wrap"), {
				defaultClass: "ztreeDefault",
				showRoot: !0
			}, {
				callback: {
					beforeClick: function(a, b) {
						queryConditions.assistId = b.id, $("#search").trigger("click")
					}
				}
			})
		},
		loadGrid: function() {
			var ordert=$("#ordert").val();
			function a(a, b, c) {
				var d = '<div class="operating" data-id="' + c.id + '"><a class="ui-icon ui-icon-search" title="查询"></a></div>';
				return d
			}
			$(window).height() - $(".grid-wrap").offset().top - 84;
			$("#grid").jqGrid({
				url: "../basedata/inventory/ordergoods",
				mtype: "POST",
				postData: queryConditions,
				ajaxGridOptions: { type: "POST" },
				serializeGridData: function(postData) {
					var d = postData || {};
					d.action = "ordergoods";
					d.locationName = ($("#locationNameCon").val() || "").trim();
					d.skey = ($("#matchCon").val() || "").trim();
					return d;
				},
				datatype: "json",
				width: 800,
				height: 354,
				altRows: !0,
				gridview: !0,
				colModel: [{
					name: "id",
					label: "ID",
					width: 0,
					hidden: !0
				}, 
				
				/*{
					name: "operating",
					label: "操作",
					width: 60,
					fixed: !0,
					formatter: a,
					align: "center"
				}, 
				*/
				
				{
					name: "number",
					label: "商品编号",
					width: 100,
					title: !1
				}, {
					name: "mdescription",
					label: "物料描述",
					width: 150,
					classes: "ui-ellipsis"
				}, {
					name: "skuClassId",
					label: "skuClassId",
					width: 0,
					hidden: !0
				}, {
					name: "skuId",
					label: "skuId",
					width: 0,
					hidden: !0
				}, {
					name: "skuName",
					label: "属性",
					width: 100,
					hidden: !skuMult,
					classes: "ui-ellipsis"
				}/*, {
					name: "qty",
					label: "数量",
					width: 60,
					hidden: !skuMult,
					formatter: function(a) {
						return a || "&#160;"
					}
				}/*, {
					name: "spec",
					label: "规格型号",
					width: 106,
					title: !1
				}*/
				, {
					name: "inventoryNew",
					label: "库存数量",
					width: 100,
					title: !1
				}, {
					name: "mainUnit",
					label: "单位",
					width: 60,
					title: !1
				}, {
					name: "price",
					label: "单价",
					width: 80,
					title: !1
				}, /*{
					name: "amount",
					label: "出库金额",
					width: 60,
					title: !1
				},*/ {
					name: "orderid",
					label: "订单号",
					width: 100,
					title: !1
				}, {
					name: "locationName",
					label: "仓库名称",
					width: 100,
					//hidden: !0
				}, {
					name: "beizhu",
					label: "项目备注",
					width: 160,
					//hidden: !0
				}/*, {
					name: "unitId",
					label: "单位ID",
					width: 0,
					hidden: !0
				}, {
					name: "salePrice",
					label: "销售单价",
					width: 0,
					hidden: !0
				}, {
					name: "purPrice",
					label: "采购单价",
					width: 0,
					hidden: !0
				}, {
					name: "locationId",
					label: "仓库ID",
					width: 0,
					hidden: !0
				}, {
					name: "locationName",
					label: "仓库名称",
					width: 0,
					hidden: !0
				}, {
					name: "isSerNum",
					label: "是否启用序列号",
					width: 0,
					hidden: !0
				}*/],
				cmTemplate: {
					sortable: !1
				},
				multiselect: isSingle ? !1 : !0,
				page: 1,
				sortname: "number",
				sortorder: "desc",
				pager: "#page",
				page: 1,
				rowNum: 100,
				rowList: [100, 200, 500],
				viewrecords: !0,
				shrinkToFit: !0,
				forceFit: !1,
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					total: "data.total",
					repeatitems: !1,
					id: "id"
				},
				loadError: function() {},
				ondblClickRow: function() {
					isSingle && (callback(), frameElement.api.close())
				},
				onSelectRow: function(a, b) {
					if (b) {
						var c = $grid.jqGrid("getRowData", a);
						skuMult && c.skuClassId > 0 ? ($("#grid").jqGrid("setSelection", a, !1), $.dialog({
							width: 470,
							height: 400,
							title: "选择【" + c.number + " " + c.name + "】的属性",
							content: "url:http://" + defaultPage.location.hostname + "/settings/assistingProp-batch.jsp",
							data: {
								isSingle: isSingle,
								skey: "",
								skuClassId: c.skuClassId,
								callback: function(b, d) {
									for (var e = [], f = 0, g = b.length; g > f; f++) {
										var h = b[f],
											i = $.extend(!0, {}, c);
										if (i.skuName = h.skuName, i.skuId = h.skuId, i.qty = h.qty, 0 === f) $("#grid").jqGrid("setRowData", a, i);
										else {
											var j = f;
											!
											function l() {
												$("#" + a + "_" + j).length && (j++, l())
											}(), i.id = a + "_" + j, $("#grid").jqGrid("addRowData", i.id, i, "after", a)
										}
										addList[i.id] = i, e.push(i)
									}
									for (var f = 0; f < e.length; f++) {
										var k = $("#" + e[f].id).find("input:checkbox")[0];
										k && !k.checked && $("#grid").jqGrid("setSelection", e[f].id, !1)
									}
									d.close()
								}
							},
							init: function() {},
							lock: !0,
							ok: !1,
							cancle: !1
						})) : addList[a] = c
					} else addList[a] && delete addList[a]
				},
				onSelectAll: function(a, b) {
					for (var c = 0, d = a.length; d > c; c++) {
						var e = a[c];
						if (b) {
							var f = $grid.jqGrid("getRowData", e);
							addList[e] = f
						} else addList[e] && delete addList[e]
					}
				},
				gridComplete: function() {
					for (_item in addList) {
						var a = $("#" + addList[_item].id);
						!a.length && a.find("input:checkbox")[0].checked && $grid.jqGrid("setSelection", _item, !1)
					}
				}
			})
		},
		reloadData: function(a) {
			var ordert=$("#ordert").val();
			addList = {};
			var postData = $.extend({}, queryConditions, a || {});
			postData.locationName = ($("#locationNameCon").val() || "").trim();
			postData.skey = ($("#matchCon").val() || "").trim();
			postData.action = "ordergoods";
			$("#grid").jqGrid("setGridParam", {
				url: "../basedata/inventory/ordergoods",
				mtype: "POST",
				datatype: "json",
				postData: postData,
				ajaxGridOptions: { type: "POST" }
			}).trigger("reloadGrid");
		},
		addEvent: function() {
			var a = this;
			$(".grid-wrap").on("click", ".ui-icon-search", function(a) {
				a.preventDefault();
				var b = $(this).parent().data("id");
				Business.forSearch(b, "")
			}), $(".grid-wrap").on("click", ".ui-icon-copy", function(a) {
				a.preventDefault();
				var b = $(this).parent().data("id"),
					c = "商品图片";
				parent.$.dialog({
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
			}), $(".mod-search .search-input-wrap input").on("input keyup", function() {
				$(this).closest(".search-input-wrap").toggleClass("has-text", $(this).val().length > 0);
			}), $(".mod-search .search-input-wrap input").on("blur", function() {
				var inp = $(this), v = (inp.val() || "").trim();
				inp.val(v);
				inp.closest(".search-input-wrap").toggleClass("has-text", v.length > 0);
			}), $(".mod-search .search-input-wrap input").each(function() {
				$(this).closest(".search-input-wrap").toggleClass("has-text", $(this).val().length > 0);
			}), $(".mod-search .input-clear").on("click", function() {
				var w = $(this).closest(".search-input-wrap"), inp = w.find("input");
				inp.val(""), w.removeClass("has-text"), inp.focus();
			}), $("#search").click(function() {
				queryConditions.catId = a.catId;
				queryConditions.skey = ($("#matchCon").val() || "").trim();
				queryConditions.locationName = ($("#locationNameCon").val() || "").trim();
				$("#matchCon").val(queryConditions.skey);
				$("#locationNameCon").val(queryConditions.locationName);
				$(".mod-search .search-input-wrap").each(function() {
					$(this).toggleClass("has-text", $(this).find("input").val().length > 0);
				});
				a.reloadData($.extend({ page: 1 }, queryConditions));
			}), $("#refresh").click(function() {
				a.$_matchCon.val(""), a.$_locationNameCon && a.$_locationNameCon.val(""), queryConditions.skey = "", queryConditions.locationName = "", queryConditions.catId = "", $(".mod-search .search-input-wrap").removeClass("has-text"), a.reloadData(queryConditions);
			})
		}
	};
THISPAGE.init();