$(function() {
	function a() {
		e = Business.categoryCombo($("#catorage"), {
			editable: !1,
			extraListHtml: "",
			addOptions: {
				value: -1,
				text: "选择项目类别"
			},
			defaultSelected: 0,
			trigger: !0,
			width: 120
		}, "customertype")
	}
	function b() {
		var a = Public.setGrid(),
			b = !(parent.SYSTEM.isAdmin || parent.SYSTEM.rights.AMOUNT_OUTAMOUNT),
			c = [{
				name: "operate",
				label: "操作",
				width: 60,
				fixed: !0,
				formatter: Public.operFmatter,
				title: !1
			}, {
				name: "customerType",
				label: "项目类别",
				index: "customerType",
				width: 100,
				fixed: !0,
				title: !1
			}, {
				name: "number",
				label: "项目编号",
				index: "number",
				width: 100,
				title: !1
			}, {
				name: "name",
				label: "项目名称",
				index: "name",
				width: 530,
				classes: "ui-ellipsis"
			},
			/*
			{
				name: "contacter",
				label: "联系人",
				index: "contacter",
				width: 80,
				align: "center",
				fixed: !0
			}, {
				name: "mobile",
				label: "手机",
				index: "mobile",
				width: 100,
				align: "center",
				title: !1
			}, {
				name: "telephone",
				label: "座机",
				index: "telephone",
				width: 100,
				title: !1
			}, {
				name: "linkIm",
				label: "QQ/MSN",
				index: "linkIm",
				width: 80,
				title: !1
			}, {
				name: "difMoney",
				label: "期初往来余额",
				index: "difMoney",
				width: 100,
				align: "right",
				title: !1,
				formatter: "currency",
				hidden: b
			}, {
				name: "deliveryAddress",
				label: "送货地址",
				index: "deliveryAddress",
				width: 200,
				classes: "ui-ellipsis",
				formatter: function(a, b, c) {
					return (c.province || "") + (c.city || "") + (c.county || "") + (a || "")
				}
			},
			*/
			 {
				name: "delete",
				label: "状态",
				index: "delete",
				width: 80,
				align: "center",
				formatter: d
			},
                {
                    name: "wbs",
                    label: "WBS元素号",
                    index: "wbs",
                    width: 100,
                    title: !1
                },
                {
                    name: "gdnumber",
                    label: "工单号",
                    index: "number",
                    width: 100,
                    title: !1
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
                    label: "是否报审",
                    index: "apply",
                    width: 80,
                    align: "center",
                    formatter: d2
                },{
                    name: "remark_",
                    label: "备注",
                    index: "remark_",
                    width: 100,
                    title: !1
                },{
                    name: "orders_num",
                    label: "物资数统计",
                    index: "orders_num",
                    width: 100,
                    title: !1
                },{
                    name: "check",
                    label: "是否核对",
                    index: "check",
                    width: 80,
                    align: "center",
                    formatter: d3
                },{
                    name: "xd_name",
                    label: "下达名称",
                    index: "xd_name",
                    width: 100,
                    title: !1
                },{
                    name: "xd_order",
                    label: "下达编号",
                    index: "xd_order",
                    width: 100,
                    title: !1
                }
			];
		h.gridReg("grid", c), c = h.conf.grids.grid.colModel, $("#grid").jqGrid({
			url: "../basedata/contact?action=list&isDelete=2",
			datatype: "json",
			autowidth: !0,
			height: a.h,
			altRows: !0,
			gridview: !0,
			onselectrow: !1,
			multiselect: !0,
			colModel: c,
			pager: "#page",
			viewrecords: !0,
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
				} else {
					var e = 250 === a.status ? f ? "没有满足条件的结果哦！" : "没有项目数据哦！" : a.msg;
					parent.Public.tips({
						type: 2,
						content: e
					})
				}
			},
			loadError: function(a, b, c) {
				parent.Public.tips({
					type: 1,
					content: "操作失败了哦，请检查您的网络链接！"
				})
			},
			resizeStop: function(a, b) {
				h.setGridWidthByIndex(a, b, "grid")
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
				h.config()
			},
			position: "last"
		})
	}
	function c() {
		$_matchCon = $("#matchCon"), $_matchCon.placeholder(),
            $_remark_ = $("#remark_"),$_remark_.placeholder(),
			$_design = $("#design"),$_apply = $("#apply"),$_disable = $("#disable"),
			$("#search").on("click", function(a) {
			a.preventDefault();
			var b = "输入项目编号/ 名称" === $_matchCon.val() ? "" : $.trim($_matchCon.val()),
				b1 = "输入备注内容" === $_remark_.val() ? "" : $.trim($_remark_.val()),
				b3 = $_design.val(),
				b4 = $_apply.val(),
				b5 = $_disable.val(),
				c = e ? e.getValue() : -1;
			$("#grid").jqGrid("setGridParam", {
				page: 1,
				postData: {
					skey: b,
					categoryId: c,
                    remark_:b1,
					design:b3,
					apply:b4,
					disable:b5
				}
			}).trigger("reloadGrid")
		}), $("#btn-add").on("click", function(a) {
			a.preventDefault(), Business.verifyRight("BU_ADD") && g.operate("add")
		}), $("#btn-print").on("click", function(a) {
			a.preventDefault()
		}), $("#btn-import").on("click", function(a) {
			a.preventDefault(), Business.verifyRight("BaseData_IMPORT") && parent.$.dialog({
				width: 560,
				height: 300,
				title: "批量导入",
				content: "url:/import",
				lock: !0
			})
		}), $("#btn-export").on("click", function(a) {
			if (Business.verifyRight("BU_EXPORT")) {
                var b = "输入项目编号/ 名称" === $_matchCon.val() ? "" : $.trim($_matchCon.val()),
                    b1 = "输入备注内容" === $_remark_.val() ? "" : $.trim($_remark_.val()),
                    b3 = $_design.val(),
                    b4 = $_apply.val(),
                    b5 = $_disable.val(),
                    c = e ? e.getValue() : -1;
				$(this).attr("href", "../basedata/customer/exporter?action=exporter&isDelete=2&skey=" + b + '&design='+b3+'&apply='+b4+'&disable='+b5+'&remark_='+b1+'&categoryId='+ c)
			}
		}), $("#grid").on("click", ".operating .ui-icon-pencil", function(a) {
			if (a.preventDefault(), Business.verifyRight("BU_UPDATE")) {
				var b = $(this).parent().data("id");
				g.operate("edit", b)
			}
		}), $("#grid").on("click", ".operating .ui-icon-trash", function(a) {
			if (a.preventDefault(), Business.verifyRight("BU_DELETE")) {
				var b = $(this).parent().data("id");
				g.del(b + "")
			}
		}), $("#btn-batchDel").click(function(a) {
			if (a.preventDefault(), Business.verifyRight("BU_DELETE")) {
				var b = $("#grid").jqGrid("getGridParam", "selarrrow");
				b.length ? g.del(b.join()) : parent.Public.tips({
					type: 2,
					content: "请选择需要删除的项"
				})
			}
		}), $("#btn-disable").click(function(a) {
			if(a.preventDefault(), Business.verifyRight("BU_DISABLE")){
                var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
                return b && 0 != b.length ? void g.setStatuses(b, !0) : void parent.Public.tips({
                    type: 1,
                    content: " 请先选择要禁用的项目！"
                })
			}

		}), $("#btn-enable").click(function(a) {
            if(a.preventDefault(), Business.verifyRight("BU_ENABLE")){
                var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
                return b && 0 != b.length ? void g.setStatuses(b, !1) : void parent.Public.tips({
                    type: 1,
                    content: " 请先选择要启用的项目！"
                })
            }

		}),$("#btn-no-design").click(function(a) {
            if(a.preventDefault(), Business.verifyRight("BU_NODESIGN")){
                var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
                return b && 0 != b.length ? void g.setDesigns(b, !1) : void parent.Public.tips({
                    type: 1,
                    content: " 请先选择要设为‘未设计’的项目！"
                })
            }

        }), $("#btn-is-design").click(function(a) {
            if(a.preventDefault(), Business.verifyRight("BU_ISDESIGN")){
                var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
                return b && 0 != b.length ? void g.setDesigns(b, !0) : void parent.Public.tips({
                    type: 1,
                    content: " 请先选择要设为‘已设计’的项目！"
                })
            }

        }), $("#btn-no-apply").click(function(a) {
            if(a.preventDefault(), Business.verifyRight("BU_NOAPPLY")){
                var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
                return b && 0 != b.length ? void g.setApplys(b, !1) : void parent.Public.tips({
                    type: 1,
                    content: " 请先选择要设为‘未申请’的项目！"
                })
            }

        }), $("#btn-is-apply").click(function(a) {
            if(a.preventDefault(), Business.verifyRight("BU_ISAPPLY")){
                var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
                return b && 0 != b.length ? void g.setApplys(b, !0) : void parent.Public.tips({
                    type: 1,
                    content: " 请先选择要设为‘已申请’的项目！"
                })
            }

        }), $("#btn-is-check").click(function(a) {
            if(a.preventDefault(), Business.verifyRight("BU_ISCHECK")){
                var b = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
                return b && 0 != b.length ? void g.setChecks(b, !0) : void parent.Public.tips({
                    type: 1,
                    content: " 请先选择要设为‘已核对’的项目！"
                })
            }

        }),$("#grid").on("click", ".set-status", function(a) {
			if (a.stopPropagation(), a.preventDefault(), Business.verifyRight("BU_DISABLE"), Business.verifyRight("BU_ENABLE")) {
				var b = $(this).data("id"),
					c = !$(this).data("delete");
				g.setStatus(b, c)
			}
		}), $("#grid").on("click", ".set-design", function(a) {
            if (a.stopPropagation(), a.preventDefault(), Business.verifyRight("BU_ISDESIGN"), Business.verifyRight("BU_NODESIGN")) {
                var b = $(this).data("id"),
                    c = !$(this).data("design");
                g.setDesign(b, c)
            }
        }),$("#grid").on("click", ".set-apply", function(a) {
            if (a.stopPropagation(), a.preventDefault(), Business.verifyRight("BU_ISAPPLY"), Business.verifyRight("BU_NOAPPLY")) {
                var b = $(this).data("id"),
                    c = !$(this).data("apply");
                g.setApply(b, c)
            }
        }),$("#grid").on("click", ".ui-label-success", function(a) {
            if (a.stopPropagation(), a.preventDefault(), Business.verifyRight("BU_ISCHECK"), Business.verifyRight("BU_NOCHECK")) {
                var b = $(this).data("id"),
                    c = !$(this).data("check");
                g.setCheck(b, c)
            }
        }),$(".wrapper").on("click", "#daoru", function(a) {
				//if (b.preventDefault(), Business.verifyRight("PU_UNCHECK")) {
					var c = $(this);

					$.dialog({
						content: "url:../settings/Contractorderc",
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


			}), $(window).resize(function() {
			Public.resizeGrid()
		})
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
	var e, f = !1,
		g = {
			operate: function(a, b) {
				if ("add" == a) var c = "新增项目",
					d = {
						oper: a,
						callback: this.callback
					};
				else var c = "修改项目",
					d = {
						oper: a,
						rowId: b,
						callback: this.callback
					};
				$.dialog({
					title: c,
					content: "url:customer_manage",
					data: d,
					width: 640,
					height: 466,
					max: !1,
					min: !1,
					cache: !1,
					lock: !0
				})
			},
			del: function(a) {
				$.dialog.confirm("删除的项目将不能恢复，请确认是否删除？", function() {
					Public.ajaxPost("../basedata/contact/delete?action=delete", {
						id: a
					}, function(b) {
						if (b && 200 == b.status) {
							var c = b.data.id || [];
							a.split(",").length === c.length ? parent.Public.tips({
								content: "成功删除" + c.length + "个项目！"
							}) : parent.Public.tips({
								type: 2,
								content: b.data.msg
							});
							for (var d = 0, e = c.length; e > d; d++) $("#grid").jqGrid("setSelection", c[d]), $("#grid").jqGrid("delRowData", c[d])
						} else parent.Public.tips({
							type: 1,
							content: "删除项目失败！" + b.msg
						})
					})
				})
			},
			setStatus: function(a, b) {
				a && Public.ajaxPost("../basedata/contact/disable?action=disable", {
					contactIds: a,
					disable: Number(b)
				}, function(c) {
					c && 200 == c.status ? (parent.Public.tips({
						content: "项目状态修改成功！"
					}), $("#grid").jqGrid("setCell", a, "delete", b)) : parent.Public.tips({
						type: 1,
						content: "项目状态修改失败！" + c.msg
					})
				})
			},
			setStatuses: function(a, b) {
				if (a && 0 != a.length) {
					var c = $("#grid").jqGrid("getGridParam", "selarrrow"),
						d = c.join();
					Public.ajaxPost("../basedata/contact/disable?action=disable", {
						contactIds: d,
						disable: Number(b)
					}, function(c) {
						if (c && 200 == c.status) {
							parent.Public.tips({
								content: "项目状态修改成功！"
							});
							for (var d = 0; d < a.length; d++) {
								var e = a[d];
								$("#grid").jqGrid("setCell", e, "delete", b)
							}
						} else parent.Public.tips({
							type: 1,
							content: "项目状态修改失败！" + c.msg
						})
					})
				}
			},


            setDesign: function(a, b) {
                $.dialog.confirm("是否更改设计状态？", function() {
                    a && Public.ajaxPost("../basedata/contact/design?action=design", {
                        contactIds: a,
                        design: Number(b)
                    }, function(c) {
                        c && 200 == c.status ? (parent.Public.tips({
                            content: "设计状态修改成功！"
                        }), $("#grid").jqGrid("setCell", a, "design", b)) : parent.Public.tips({
                            type: 1,
                            content: "设计状态修改失败！" + c.msg
                        })
                    })
                })

            },
            setDesigns: function(a, b) {
                $.dialog.confirm("是否更改设计状态？", function() {
                    if (a && 0 != a.length) {
                        var c = $("#grid").jqGrid("getGridParam", "selarrrow"),
                            d = c.join();
                        Public.ajaxPost("../basedata/contact/design?action=design", {
                            contactIds: d,
                            design: Number(b)
                        }, function(c) {
                            if (c && 200 == c.status) {
                                parent.Public.tips({
                                    content: "设计状态修改成功！"
                                });
                                for (var d = 0; d < a.length; d++) {
                                    var e = a[d];
                                    $("#grid").jqGrid("setCell", e, "design", b)
                                }
                            } else parent.Public.tips({
                                type: 1,
                                content: "设计状态修改失败！" + c.msg
                            })
                        })
                    }
                })
            },

            setApply: function(a, b) {
                $.dialog.confirm("是否更改申请状态？", function() {
                    a && Public.ajaxPost("../basedata/contact/apply?action=apply", {
                        contactIds: a,
                        apply: Number(b)
                    }, function(c) {
                        c && 200 == c.status ? (parent.Public.tips({
                            content: "申请状态修改成功！"
                        }), $("#grid").jqGrid("setCell", a, "apply", b)) : parent.Public.tips({
                            type: 1,
                            content: "申请状态修改失败！" + c.msg
                        })
                    })
                })
            },
            setApplys: function(a, b) {
                $.dialog.confirm("是否更改申请状态？", function() {
                    if (a && 0 != a.length) {
                        var c = $("#grid").jqGrid("getGridParam", "selarrrow"),
                            d = c.join();
                        Public.ajaxPost("../basedata/contact/apply?action=apply", {
                            contactIds: d,
                            apply: Number(b)
                        }, function(c) {
                            if (c && 200 == c.status) {
                                parent.Public.tips({
                                    content: "申请状态修改成功！"
                                });
                                for (var d = 0; d < a.length; d++) {
                                    var e = a[d];
                                    $("#grid").jqGrid("setCell", e, "apply", b)
                                }
                            } else parent.Public.tips({
                                type: 1,
                                content: "申请状态修改失败！" + c.msg
                            })
                        })
                    }
                })
            },

            setCheck: function(a, b) {
                $.dialog.confirm("是否更改核对状态？", function() {
                    a && Public.ajaxPost("../basedata/contact/check?action=check", {
                        contactIds: a,
                        check: Number(b)
                    }, function(c) {
                        c && 200 == c.status ? (parent.Public.tips({
                            content: "核对状态修改成功！"
                        }), $("#grid").jqGrid("setCell", a, "check", b)) : parent.Public.tips({
                            type: 1,
                            content: "核对状态修改失败！" + c.msg
                        })
                    })
                })
            },
            setChecks: function(a, b) {
                $.dialog.confirm("是否更改核对状态？", function() {
                    if (a && 0 != a.length) {
                        var c = $("#grid").jqGrid("getGridParam", "selarrrow"),
                            d = c.join();
                        Public.ajaxPost("../basedata/contact/check?action=check", {
                            contactIds: d,
                            check: Number(b)
                        }, function(c) {
                            if (c && 200 == c.status) {
                                parent.Public.tips({
                                    content: "核对状态修改成功！"
                                });
                                for (var d = 0; d < a.length; d++) {
                                    var e = a[d];
                                    $("#grid").jqGrid("setCell", e, "check", b)
                                }
                            } else parent.Public.tips({
                                type: 1,
                                content: "核对状态修改失败！" + c.msg
                            })
                        })
                    }
                })
            },
			callback: function(a, b, c) {
				var d = $("#grid").data("gridData");
				d || (d = {}, $("#grid").data("gridData", d)), a.difMoney = a.amount - a.periodMoney, d[a.id] = a, "edit" == b ? ($("#grid").jqGrid("setRowData", a.id, a), c && c.api.close()) : ($("#grid").jqGrid("addRowData", a.id, a, "first"), c && c.resetForm(a))
			}
		},
		h = Public.mod_PageConfig.init("customerList");
	a(), b(), c()
});








