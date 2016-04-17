
//查看详情
function detailAction(id)
{
    window.location.href = 'detail/id/'+id;
}

//编辑
function editAction(id)
{
    window.location.href = 'input/id/'+id;
}

//删除
function deleteAction(id)
{
    if (confirm("确认要删除吗？")) {
        $.ajax({
            type: "POST",
            url: "delete",
            data: {id:id},
            success: function(data) {
                console.log(data);
                if(data.status=="200"){
                    alert(data.message);
                    $("#_CustomizedQueryFormSubmit").click();
                }else{
                    alert(data.message);
                }
            }
        });  
    };
}

//新增或修改
function dataSave(submit_button='submit_button',url='inputAjax')
{
    $('#'+submit_button).on('click',function(){
        var data = $('#form_data').serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(data) {
                console.log(data);
                if(data.status=="200"){
                    alert(data.message);
                    
                }else{
                    alert(data.message);
                }
            }
        }); 
    });
}