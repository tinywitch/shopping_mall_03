$(document).ready(function(){
    function load_district()
    {
        var province_id = $('#province option:selected').val();
        $.ajax({
            type: 'POST',
            url: '/loaddistrict',
            data: {
                'province_id' : province_id,
            },
            success: function(data) {
                var data = JSON.parse(data);
                $('#district').find('option').remove();
                for (var k in data){
                    $('#district').append('<option value="' + k + '">' + data[k] + '</option>');
                }                               
            }
        })
    }

    $('#province').on("change", function(){
        load_district();
    });
});
