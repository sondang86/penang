var mainurl = '../api/index.php';

$(function(){
    $("table.t tr:nth-child(even)").addClass("even");
    
    $('.errMessage span, .succMessage span').click(function(){
        $(this).parent().fadeOut();
    });
    
//    $('.datepicker').datepicker({
//        changeMonth: true,
//        changeYear: true,
//        dateFormat: 'dd/mm/yy'
//    });
    
    $('.tip').tipr();
    
    $('#state_list').change(function(){
        $('#city_list, #area_list').html('<option value="">- Select - </option>');
        $.get(mainurl, {submit: 'getCityByState', state: $(this).val()}, function(data){
            if(!data['err']){
                var ddl = '<option value="">- Select - </option>';
                for(var i=0;i<data.length;i++){
                    ddl += '<option value="'+data[i]['id']+'" data-location="'+data[i]['latitude']+','+data[i]['longitude']+'">'+data[i]['name']+'</option>';
                }
                $('#city_list').html(ddl).val($('#hid_city').val());
            }
        }, 'json');
        $.get(mainurl, {submit: 'getAreaByState', state: $(this).val()}, function(data){
            if(!data['err']){
                var ddl = '<option value="">- Select - </option>';
                for(var i=0;i<data.length;i++){
                    ddl += '<option value="'+data[i]['id']+'" data-location="'+data[i]['latitude']+','+data[i]['longitude']+'">'+data[i]['name']+'</option>';
                }
                $('#area_list').html(ddl).val($('#hid_area').val());
            }
        }, 'json');
    });
});

function wopen(url, name, w, h){
    wleft = (screen.width - w) / 2;
    wtop = ((screen.height - h) / 2)-10;
    var win = window.open(url,
        name,
        'width=' + w + ', height=' + h + ', ' +
        'left=' + wleft + ', top=' + wtop + ', ' +
        'location=no, menubar=no, ' +
        'status=no, toolbar=no, scrollbars=yes, resizable=yes');
}

function redirect(url){
    window.location = url;
}