var string='';
var billNoRequestStatus = {};
//监控键盘输入
function keyUp(e) {
    var currKey=0,e=e||event;
    currKey=e.keyCode||e.which||e.charCode;
    var keyName = String.fromCharCode(currKey);
    string += keyName;
    /*if(currKey==37){
        console.log('左键')
    }
    if(currKey==39){
        console.log('邮件')
        $('#next').click();
    }*/
}
document.onkeyup = keyUp;
//验证字符串是否是数字
function checkNumber(theObj) {
    var reg = /^[0-9]+.?[0-9]*$/;
    if (reg.test(theObj)) {
        return true;
    }
    return false;
}
//获取出库单信息
function getChukuIds(billNo){
    $.ajax({
        url: "../basedata/inventory/getChuku",
        dataType:'json',
        data:{"billNo": billNo},
        success: function(e){
            if(e.error_code == '0000'){

            }else{
                return false;
            }
        }
    });
}
//提交sting
/*daohuo('4510153405');*/
function daohuo(ordernumber){
    $.ajax({
        url: "../basedata/inventory/getDaohuo",
        dataType:'json',
        data:{"ordernumber": ordernumber},
        success: function(e){
            if(e.error_code == '0000'){
                var a = e.data['ids'];
                Public.ajaxPost("../basedata/inventory/querydaohuo?action=querydaohuo", {
                    id: a,
                }, function(b) {
                    if (b && 200 == b.status) {
                        parent.Public.tips({
                            type: 1,
                            content: "物资已到货，请勿重复操作！！"
                        });
                    }else {
                        parent.Public.tips({
                        content: "到货情况正常"
                    });
                        $.dialog({
                            width: 775,
                            height: 510,
                            title: '到货',
                            content: 'url:../settings/goods_daohuobatch_scan?id='+a,
                            data: {
                                //skuMult: skuMult,
                                //skey:_self.skey,
                                callback: function(newId, curID, curRow){

                                    if(curID === '') {
                                        $("#grid").jqGrid('addRowData', newId, {}, 'last');
                                        _self.newId = newId + 1;
                                    };
                                    setTimeout( function() { $("#grid").jqGrid("editCell", curRow, 2, true) }, 10);
                                    _self.calTotal();
                                }
                            },
                            lock: true,
                            close: function() {window.location.reload();}
                        });

                    }
                });
            }else{
                parent.Public.tips({
                    type: 1,
                    content: "未查到相关记录,请重新操作！！"
                });
                return false;
            }
        }
    });


}
// /*chuku('LLD00035');*/
// function chuku(billNo){
//     $.ajax({
//         url: "../basedata/inventory/getChuku",
//         dataType:'json',
//         data:{"billNo": billNo},
//         success: function(e){
//             if(e.error_code == '0000'){
//                 var a = e.data['ids'];
//                 Public.ajaxPost("../basedata/inventory/querychuku?action=querychuku", {
//                     id: a,billNo:billNo
//                 }, function(b) {
//                     if (b && 200 == b.status) {
//                         parent.Public.tips({
//                             type: 1,
//                             content: b.msg
//                         });
//                     }else {
//                         parent.Public.tips({
//                         content: "到货情况正常"
//                          });
//                         $.dialog({
//                             width: 775,
//                             height: 510,
//                             title: '出库',
//                             content: 'url:../settings/goods_chukubatch_scan?id='+a+'&liname='+e.data['liname']+'&billNo='+e.data['billNo'],
//                             data: {
//                                 //skuMult: skuMult,
//                                 //skey:_self.skey,
//                                 callback: function(newId, curID, curRow){

//                                     if(curID === '') {
//                                         $("#grid").jqGrid('addRowData', newId, {}, 'last');
//                                         _self.newId = newId + 1;
//                                     };
//                                     setTimeout( function() { $("#grid").jqGrid("editCell", curRow, 2, true) }, 10);
//                                     _self.calTotal();
//                                 }
//                             },
//                             lock: true,
//                             close: function() {window.location.reload();}
//                         });

//                     }
//                 });
//             }else{
//                 parent.Public.tips({
//                     type: 1,
//                     content: "未查到相关记录,请重新操作！！！"
//                 });
//                 return false;
//             }
//         }
//     });
// }
// 每多少秒判断清空一次string
// function timeCutString(){
//     var len = string.length;

//     if(len>7){
//         if(string.substring(0,4)=='LLD'){
//             chuku(string);
//         }
//     }
//     if(len>9){
//         daohuo(string);
//     }
//     string='';
// }
// 使用一个对象存储每个 billNo 的请求状态
function chuku(billNo) {
    // 检查 billNo 是否正在处理，并添加到 billNoRequestStatus
    if (billNoRequestStatus[billNo] === true) {
        // 如果请求正在处理，不执行任何操作
        return;
    }

    // 设置 billNo 的请求状态为正在进行
    billNoRequestStatus[billNo] = true;

    $.ajax({
        url: "../basedata/inventory/getChuku",
        dataType: 'json',
        data: { "billNo": billNo },
        success: function (e) {
            if (e.error_code == '0000') {
                var a = e.data['ids'];
                Public.ajaxPost("../basedata/inventory/querychuku?action=querychuku", {
                    id: a,
                    billNo: billNo
                }, function (b) {
                    if (b && 200 == b.status) {
                        parent.Public.tips({
                            type: 1,
                            content: b.msg
                        });
                    } else {
                        parent.Public.tips({
                            content: "到货情况正常"
                        });
                        $.dialog({
                            width: 775,
                            height: 510,
                            title: '出库',
                            content: 'url:../settings/goods_chukubatch_scan?id=' + a + '&liname=' + e.data['liname'] + '&billNo=' + e.data['billNo'],
                            data: {
                                callback: function (newId, curID, curRow) {
                                    if (curID === '') {
                                        $("#grid").jqGrid('addRowData', newId, {}, 'last');
                                    };
                                    setTimeout(function () { $("#grid").jqGrid("editCell", curRow, 2, true) }, 10);
                                }
                            },
                            lock: true,
                            close: function () { window.location.reload(); }
                        });
                    }
                    
                    // 请求完成后，重置 billNo 的请求状态
                    billNoRequestStatus[billNo] = false;
                });
            } else {
                parent.Public.tips({
                    type: 1,
                    content: "未查到相关记录,请重新操作！！"
                });
                
                // 请求完成后，重置 billNo 的请求状态
                billNoRequestStatus[billNo] = false;
                return false;
            }
        }
    });
}
function timeCutString() {
    string = string.replace(/[\x00-\x1F\x7F-\xFF]/g, '');
    if (string.length > 7) {
        console.log(string)
        // 如果字符串以LLD开头，后面跟着5位数字
        if (/^LLD\d{5}$/.test(string)) {
            console.log(1)
            chuku(string);
        } else {
            daohuo(string);
        }
    }
    // 将string置空
    string = '';
}
window.setInterval("timeCutString()",2000);


function tojson(arr){
    if(!arr.length) return null;
    var i = 0;
    len = arr.length,
        array = [];
    for(;i<len;i++){
        array.push({"projectname":arr[i][0],"projectnumber":arr[i][1]});
    }
    return JSON.stringify(array);
}