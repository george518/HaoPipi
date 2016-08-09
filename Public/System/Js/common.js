
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
function dataSave(url='inputAjax',returnUrl='index',submit_button='submit_button')
{

    $('#'+submit_button).on('click',function(){
        var formdata = $('#form_data').serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: formdata,
            success: function(data) {
                console.log(data);
                if(data.status=="200"){
                    alert(data.message);
                    window.location.href = returnUrl;
                }else{
                    alert(data.message);
                }
            }
        }); 
    });
}